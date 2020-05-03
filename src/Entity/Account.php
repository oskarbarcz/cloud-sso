<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Main account instance
 *
 * @ORM\Entity(repositoryClass="App\Repository\AccountRepository")
 */
class Account implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

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

    public function getId(): ?int
    {
        return $this->id;
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
    public function getUsername(): string
    {
        if ($this->name && $this->surname) {
            return "{$this->name} {$this->surname}";
        }

        return $this->getEmail();
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
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
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
}
