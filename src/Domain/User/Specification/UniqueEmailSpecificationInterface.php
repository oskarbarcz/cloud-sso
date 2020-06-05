<?php

declare(strict_types=1);

namespace App\Domain\User\Specification;

interface UniqueEmailSpecificationInterface
{
    /**
     * @throws EmailAlreadyExistException
     */
    public function isUnique(Email $email): bool;
}
