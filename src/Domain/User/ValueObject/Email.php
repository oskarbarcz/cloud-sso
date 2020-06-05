<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

use Assert\Assertion;
use Assert\AssertionFailedException;

class Email
{
    private string $email;

    private function __construct(string $email)
    {
        $this->email = $email;
    }

    /**
     * @param string $email
     * @return Email
     * @throws AssertionFailedException
     */
    public static function fromString(string $email): self
    {
        Assertion::email($email, 'Not a valid email');

        return new self($email);
    }

    public function toString(): string
    {
        return $this->email;
    }

    public function __toString(): string
    {
        return $this->email;
    }
}