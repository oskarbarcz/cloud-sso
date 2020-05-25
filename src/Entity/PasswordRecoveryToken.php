<?php

namespace App\Entity;

use App\Repository\PasswordRecoveryTokenRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PasswordRecoveryTokenRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class PasswordRecoveryToken implements Uniquable
{
    public const UUID_LENGTH = 6;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=6)
     */
    private ?string $uuid = null;

    /**
     * @ORM\OneToOne(targetEntity=Account::class, inversedBy="passwordRecoveryToken")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Account $account = null;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private ?string $token = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTime $validUntil = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(?string $uuid): self
    {
        $this->uuid = $uuid;
        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(Account $account): self
    {
        $this->account = $account;
        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    public function getValidUntil(): ?DateTimeInterface
    {
        return $this->validUntil;
    }

    /** @ORM\PrePersist() */
    public function setValidUntil(): void
    {
        $this->validUntil = new DateTime('now + 24 hours');
    }

    public function isValid(): bool
    {
        return true;
    }
}
