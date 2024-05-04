<?php

declare(strict_types=1);

namespace App\Dto;

use DateTimeImmutable;

readonly class CountRequestDto
{
    public function __construct(
        public ?array $serviceNames = null,
        public ?int $statusCode = null,
        public ?DateTimeImmutable $startDate = null,
        public ?DateTimeImmutable $endDate = null,
    ) {
    }
}
