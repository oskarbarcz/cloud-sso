<?php

declare(strict_types=1);

namespace App\Controller\RestApi;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/api/account/current", name="api_account_current")
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function getOne(SerializerInterface $serializer): JsonResponse
    {
        $content = $serializer->serialize($this->getUser(), 'json');
        return new JsonResponse($content, 200, [], true);
    }
}
