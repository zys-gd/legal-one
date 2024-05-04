<?php

declare(strict_types=1);

namespace App\Repository\Log;

use App\Dto\CountRequestDto;
use App\Entity\Log;
use Doctrine\ORM\EntityManagerInterface;

class LogRepository
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function insertBatch(array $logs): void
    {
        foreach ($logs as $log) {
            $this->em->persist($log);
        }
        $this->em->flush();
    }

    public function search(CountRequestDto $filter)
    {
        $qb = $this->em->createQueryBuilder()
            ->select('COUNT(l.id)')
            ->from(Log::class, 'l');

        if ($filter->statusCode) {
            $qb->andWhere('l.statusCode = :statusCode')
                ->setParameter('statusCode', $filter->statusCode);
        }

        if ($filter->startDate) {
            $qb->andWhere('l.datetime >= DATE(:startDate)')
                ->setParameter('startDate', $filter->startDate->format('c'));
        }

        if ($filter->endDate) {
            $qb->andWhere('l.datetime <= DATE(:endDate)')
                ->setParameter('endDate', $filter->endDate->format('c'));
        }

        return $qb->getQuery()
            ->getSingleScalarResult();
    }
}
