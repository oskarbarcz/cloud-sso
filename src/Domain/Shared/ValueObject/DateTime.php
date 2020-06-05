<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

use App\Domain\Shared\Exception\DateTimeException;
use DateTimeImmutable;
use Exception;

class DateTime
{
    public const FORMAT = 'Y-m-d\TH:i:s.uP';

    private DateTimeImmutable $dateTime;

    private function __construct(DateTimeImmutable $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    /**
     * @throws DateTimeException
     */
    public static function now(): self
    {
        return self::create();
    }

    /**
     * @param string $dateTime
     * @return DateTime
     */
    private static function create(string $dateTime = ''): self
    {
        try {
            return new self(new DateTimeImmutable($dateTime));
        } catch (Exception $e) {
            throw new DateTimeException($e);
        }
    }

    /**
     * @param string $dateTime
     * @return DateTime
     */
    public static function fromString(string $dateTime): self
    {
        return self::create($dateTime);
    }

    public function toString(): string
    {
        return $this->dateTime->format(self::FORMAT);
    }

    public function toNative(): DateTimeImmutable
    {
        return $this->dateTime;
    }
}