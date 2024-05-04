<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Log
{
    #[ORM\Id]
    #[ORM\Column(type: 'bigint')]
    #[ORM\GeneratedValue]
    private int $id;

    public function __construct(
        #[ORM\Column]
        private string $payload,
        #[ORM\Column]
        private string $service,
        #[ORM\Column]
        private DateTimeImmutable $datetime,
        #[ORM\Column]
        private string $method,
        #[ORM\Column]
        private string $resource,
        #[ORM\Column]
        private string $protocol,
        #[ORM\Column]
        private int $statusCode,
    ) {
    }
}
