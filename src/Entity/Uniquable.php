<?php

declare(strict_types=1);

namespace App\Entity;

/**
 * Provides methods to make possible entity distinction
 */
interface Uniquable
{
    public function getUuid(): ?string;

    public function setUuid(?string $uuid);

}