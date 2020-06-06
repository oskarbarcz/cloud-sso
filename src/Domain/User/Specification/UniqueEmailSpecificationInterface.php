<?php

declare(strict_types=1);

namespace App\Domain\User\Specification;

use App\Domain\User\ValueObject\Email;

interface UniqueEmailSpecificationInterface
{
    /**
     * @param Email $email
     * @return bool
     */
    public function isUnique(Email $email): bool;
}
