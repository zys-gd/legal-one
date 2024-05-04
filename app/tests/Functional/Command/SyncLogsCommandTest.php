<?php

declare(strict_types=1);

namespace App\Tests\Functional\Command;

use RuntimeException;
use SplFileObject;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class SyncLogsCommandTest extends WebTestCase
{
    public function testSuccess(): void
    {
        $application = new Application(self::bootKernel());

        //clean cache
        $command = $application->find('c:c');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            '-e' => 'test',
        ]);

        // test command
        $command = $application->find('app:sync:logs');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'logPath' => __DIR__ . '/resource/logs.log',
        ]);

        $commandTester->assertCommandIsSuccessful();
        $this->assertEquals("Start to read log file from line 0\nLines were read: 5\n", $commandTester->getDisplay());
    }

    public function testWithWrongFile(): void
    {
        $application = new Application(self::bootKernel());

        $command = $application->find('app:sync:logs');
        $commandTester = new CommandTester($command);
        $this->expectException(RuntimeException::class);
        $commandTester->execute([
            'logPath' => 'random.log',
        ]);
    }
}
