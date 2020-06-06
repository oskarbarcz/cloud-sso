<?php

declare(strict_types=1);

namespace App\Application\Query\Auth\GetToken;

use App\Domain\User\ValueObject\Email;
use App\Infrastructure\Share\Bus\Query\QueryInterface;
use Assert\AssertionFailedException;

class GetTokenQuery implements QueryInterface
{
    /** @psalm-readonly */
    public Email $email;

    /**
     * @param string $email
     * @throws AssertionFailedException
     */
    public function __construct(string $email)
    {
        $this->email = Email::fromString($email);
    }
}