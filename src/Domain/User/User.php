<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\Shared\Exception\DateTimeException;
use App\Domain\Shared\ValueObject\DateTime;
use App\Domain\User\Event\UserEmailChanged;
use App\Domain\User\Event\UserSignedIn;
use App\Domain\User\Event\UserWasCreated;
use App\Domain\User\Exception\InvalidCredentialsException;
use App\Domain\User\Specification\UniqueEmailSpecificationInterface as UniqueEmailSpec;
use App\Domain\User\ValueObject\Auth\Credentials;
use App\Domain\User\ValueObject\Auth\HashedPassword;
use App\Domain\User\ValueObject\Email;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Ramsey\Uuid\UuidInterface;

/**
 * @psalm-suppress MissingConstructor
 */
class User extends EventSourcedAggregateRoot
{
    /** @var UuidInterface */
    private $uuid;

    /** @var Email */
    private $email;

    /** @var HashedPassword */
    private $hashedPassword;

    /** @var DateTime */
    private $createdAt;

    /** @var DateTime|null */
    private $updatedAt;

    /**
     * @param UuidInterface   $uuid
     * @param Credentials     $credentials
     * @param UniqueEmailSpec $uniqueEmailSpec
     * @return User
     * @throws DateTimeException
     */
    public static function create(UuidInterface $uuid, Credentials $credentials, UniqueEmailSpec $uniqueEmailSpec): self
    {
        $uniqueEmailSpec->isUnique($credentials->email);

        $user = new self();
        $user->apply(new UserWasCreated($uuid, $credentials, DateTime::now()));

        return $user;
    }

    /**
     * @param Email           $email
     * @param UniqueEmailSpec $uniqueEmailSpecification
     * @throws DateTimeException
     */
    public function changeEmail(Email $email, UniqueEmailSpec $uniqueEmailSpecification): void
    {
        $uniqueEmailSpecification->isUnique($email);
        $this->apply(new UserEmailChanged($this->uuid, $email, DateTime::now()));
    }

    /**
     * @param string $plainPassword
     */
    public function signIn(string $plainPassword): void
    {
        if (!$this->hashedPassword->match($plainPassword)) {
            throw new InvalidCredentialsException('Invalid credentials entered.');
        }

        $this->apply(new UserSignedIn($this->uuid, $this->email));
    }

    public function createdAt(): string
    {
        return $this->createdAt->toString();
    }

    public function updatedAt(): ?string
    {
        return isset($this->updatedAt) ? $this->updatedAt->toString() : null;
    }

    public function email(): string
    {
        return $this->email->toString();
    }

    public function uuid(): string
    {
        return $this->uuid->toString();
    }

    public function getAggregateRootId(): string
    {
        return $this->uuid->toString();
    }

    protected function applyUserWasCreated(UserWasCreated $event): void
    {
        $this->uuid = $event->uuid;

        $this->setEmail($event->credentials->email);
        $this->setHashedPassword($event->credentials->password);
        $this->setCreatedAt($event->createdAt);
    }

    private function setEmail(Email $email): void
    {
        $this->email = $email;
    }

    private function setHashedPassword(HashedPassword $hashedPassword): void
    {
        $this->hashedPassword = $hashedPassword;
    }

    private function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @param UserEmailChanged $event
     * @throws AssertionFailedException
     */
    protected function applyUserEmailChanged(UserEmailChanged $event): void
    {
        Assertion::notEq($this->email->toString(), $event->email->toString(), 'New email should be different');

        $this->setEmail($event->email);
        $this->setUpdatedAt($event->updatedAt);
    }

    private function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}