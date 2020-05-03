<?php

declare(strict_types=1);

namespace App\Transformer;

use App\Entity\Account;
use League\Fractal\TransformerAbstract;

class AccountTransformer extends TransformerAbstract
{
    public function transform(Account $account): array
    {
        return [
            'uuid' => $account->getUuid(),
            'name' => $account->getName(),
            'surname' => $account->getSurname(),
            'email' => $account->getEmail(),
            'roles' => $account->getRoles(),
        ];
    }
}
