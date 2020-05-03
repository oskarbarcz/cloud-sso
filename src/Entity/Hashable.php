<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

interface Hashable extends UserInterface
{
    public function getPlainPassword(): ?string;

    public function setPlainPassword(?string $plainPassword): Account;

}