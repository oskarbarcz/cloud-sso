<?php

declare(strict_types=1);

namespace App\Controller\RestApi;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{
    /* private Manager $manager;

     public function __construct(Manager $manager)
     {
         $this->manager = $manager;
     }

    public function getOne(): JsonResponse
    {
        $item = new Item($this->getUser(), new AccountTransformer());
        return new JsonResponse($this->manager->createData($item)->toArray());
    }*/
}
