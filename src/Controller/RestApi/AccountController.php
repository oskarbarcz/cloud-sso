<?php

declare(strict_types=1);

namespace App\Controller\RestApi;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * @Route("/api/account/current", name="api_account_current")
     */
    public function getOne()
    {
        return new JsonResponse('Acc');
    }
}
