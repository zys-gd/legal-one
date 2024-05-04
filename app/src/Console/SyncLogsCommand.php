<?php

declare(strict_types=1);

namespace App\Console;

use App\Entity\Log;
use App\Repository\Log\LogRepository;
use DateTimeImmutable;
use Psr\Cache\CacheItemPoolInterface;
use SplFileObject;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

#[AsCommand(
    name: 'app:sync:logs',
    description: 'Parse log file with format `USER-SERVICE - - [17/Aug/2018:09:21:54 +0000] "POST /users HTTP/1.1" 400`'

)]
class SyncLogsCommand extends Command
{
    private const LOG_PATH = 'logPath';

    public function __construct(
        private readonly string $batchLinesCount,
        private readonly LogRepository $logRepository,
        private readonly CacheItemPoolInterface $cache,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument(self::LOG_PATH, InputArgument::REQUIRED, 'Absolute path to log file');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $logPath = $input->getArgument(self::LOG_PATH);
        $startLineNumber = $this->cachedReadLine($logPath);
        $output->writeln(sprintf('Start to read log file from line %s', $startLineNumber));
        $read = 0;
        /** @var array<int, string> $content */
        $content = [];
        $file = new SplFileObject($logPath);
        try {
            if (!$file->flock(LOCK_SH)) {
                $output->writeln('Log file is locked for read. Try later');

                return Command::FAILURE;
            }

            $file->setFlags(SplFileObject::SKIP_EMPTY);
            if ($file->valid()) {
                $file->seek($startLineNumber);
                while ($read < $this->batchLinesCount && $file->valid() && $line = $file->current()) {
                    $content[] = $this->parseLineAsLog($line);
                    $file->next();
                    ++$read;
                }
            }
            $this->logRepository->insertBatch(array_filter($content));
            $this->cachedReadLine($logPath, $startLineNumber + $read);
        } catch (Throwable $e) {
            $output->writeln(sprintf('Exception: %s', $e->getMessage()));

            return Command::FAILURE;
        } finally {
            $file->flock(LOCK_UN);
        }
        $output->writeln(sprintf('Lines were read: %s', $read));

        return Command::SUCCESS;
    }

    private function parseLineAsLog(string $line): ?Log
    {
        $patternService = '([\w-]+)';
        $patternTime = '([0-9A-Za-z/:+\s]+)';
        $patternMethod = '([\w]+)';
        $patternResource = '([\w/\-]+)';
        $patternProtocol = '([\w/\-.]+)';
        $patternStatusCode = '(\d+)';
        preg_match(
            sprintf(
                '#%s[\s-]+\[%s][\s]+"%s\s+%s\s+%s"[\s]+%s#',
                $patternService,
                $patternTime,
                $patternMethod,
                $patternResource,
                $patternProtocol,
                $patternStatusCode,
            ),
            $line,
            $matches
        );

        // just the simple validation that log line has expected structure
        if (count($matches) == 7) {
            // I used entity here only for type validation and simplifying repository code
            return new Log(
                payload: $matches[0],
                service: $matches[1],
                datetime: new DateTimeImmutable($matches[2]),
                method: $matches[3],
                resource: $matches[4],
                protocol: $matches[5],
                statusCode: (int) $matches[6],
            );
        }

        return null;
    }

    private function cachedReadLine(string $key, ?int $read = null): int
    {
        $key = preg_replace('#[^A-Za-z0-9\-]#', '_', $key);
        $item = $this->cache->getItem($key);
        if ($read) {
            $item->set($read);
            $item->expiresAfter(null);
            $this->cache->save($item);
        }

        return $item->isHit() ? $item->get() : 0;
    }
}
