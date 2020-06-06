<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\Auth;

use App\Application\Command\User\SignIn\SignInCommand;
use App\Application\Query\Auth\GetToken\GetTokenQuery;
use App\UI\Http\Rest\Controller\CommandQueryController;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

final class CheckController extends CommandQueryController
{
    /**
     * @Route(
     *     "/auth_check",
     *     name="auth_check",
     *     methods={"POST"},
     *     requirements={
     *      "username": "\w+",
     *      "password": "\w+"
     *     }
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Login success"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Bad request"
     * )
     * @SWG\Response(
     *     response=401,
     *     description="ad credentials"
     * )
     * @SWG\Parameter(
     *     name="credentials",
     *     type="object",
     *     in="body",
     *     schema=@SWG\Schema(type="object",
     *         @SWG\Property(property="password", type="string"),
     *         @SWG\Property(property="username", type="string")
     *     )
     * )
     *
     * @SWG\Tag(name="Auth")
     *
     * @param Request $request
     * @return JsonResponse
     * @throws AssertionFailedException
     * @throws Throwable
     */
    public function __invoke(Request $request): JsonResponse
    {
        $username = $request->get('username');

        Assertion::notNull($username, 'Username cant\'t be empty');

        $signInCommand = new SignInCommand(
            $username,
            $request->get('password')
        );

        $this->exec($signInCommand);

        return new JsonResponse(
            [
                'token' => $this->ask(new GetTokenQuery($username)),
            ]
        );
    }
}