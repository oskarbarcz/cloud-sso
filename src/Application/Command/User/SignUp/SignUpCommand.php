<?php

declare(strict_types=1);

namespace App\Application\Command\User\SignUp;

use App\Domain\User\ValueObject\Auth\Credentials;
use App\Domain\User\ValueObject\Auth\HashedPassword;
use App\Domain\User\ValueObject\Email;
use App\Infrastructure\Share\Bus\Command\CommandInterface;
use Assert\AssertionFailedException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class SignUpCommand implements CommandInterface
{
    public UuidInterface $uuid;
    public Credentials $credentials;

    /**
     * @param string $email
     * @param string $plainPassword
     * @throws AssertionFailedException
     */
    public function __construct(string $email, string $plainPassword)
    {
        $this->uuid = Uuid::uuid4();
        $this->credentials = new Credentials(Email::fromString($email), HashedPassword::encode($plainPassword));
    }
}