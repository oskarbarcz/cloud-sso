<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Main account instance
 *
 * @ORM\Entity(repositoryClass="App\Repository\AccountRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Account implements Hashable, Uniquable
{
    public const UUID_LENGTH = 10;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /** @ORM\Column(type="string", length=10) */
    private ?string $uuid = null;

    /** @ORM\Column(type="string", length=180, nullable=true) */
    private ?string $name = null;

    /** @ORM\Column(type="string", length=180, nullable=true) */
    private ?string $surname = null;

    /** @ORM\Column(type="string", length=180, unique=true) */
    private ?string $email = null;

    /** @ORM\Column(type="json") */
    private array $roles = [];

    /** @ORM\Column(type="string") */
    private ?string $password = null;

    /** @ORM\Column(type="datetime") */
    private ?DateTime $createdAt = null;

    /** @ORM\OneToOne(targetEntity=PasswordRecoveryToken::class, mappedBy="account", cascade={"persist"}) */
    private ?PasswordRecoveryToken $passwordRecoveryToken = null;

    private ?string $plainPassword = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    /** @see UserInterface */
    public function getUsername(): string
    {
        return $this->getEmail();
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /** @see UserInterface */
    public function getRoles(): array
    {
        return array_unique([...$this->roles, 'ROLE_USER']);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    /** @see UserInterface */
    public function getSalt(): void
    {
    }

    /** @see UserInterface */
    public function eraseCredentials(): void
    {
    }

    /** @see UserInterface */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(?string $uuid): Account
    {
        $this->uuid = $uuid;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): Account
    {
        $this->name = $name;
        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): Account
    {
        $this->surname = $surname;
        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): Account
    {
        $this->password = null;
        $this->plainPassword = $plainPassword;
        return $this;
    }

    /** @return DateTime */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /** @ORM\PrePersist() */
    public function setCreatedAt(): void
    {
        $this->createdAt = new DateTime();
    }

    public function getPasswordRecoveryToken(): ?PasswordRecoveryToken
    {
        return $this->passwordRecoveryToken;
    }

    public function setPasswordRecoveryToken(?PasswordRecoveryToken $passwordRecoveryToken): self
    {
        $this->passwordRecoveryToken = $passwordRecoveryToken;

        // set the owning side of the relation if necessary
        if ($passwordRecoveryToken !== null && $passwordRecoveryToken->getAccount() !== $this) {
            $passwordRecoveryToken->setAccount($this);
        }

        return $this;
    }
}
