<?php

declare(strict_types=1);

namespace App\Controller\RestApi;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class StatusController
{
    /**
     * @Route("/api/ping", name="api_ping")
     * @return JsonResponse
     */
    public function ping(): JsonResponse
    {
        return new JsonResponse('pong!');
    }
}
