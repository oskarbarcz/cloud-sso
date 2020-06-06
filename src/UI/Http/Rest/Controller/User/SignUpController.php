<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller\User;

use App\Application\Command\User\SignUp\SignUpCommand;
use App\UI\Http\Rest\Controller\CommandController;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

final class SignUpController extends CommandController
{
    /**
     * Adds new user
     *
     * @Route(
     *     "/user",
     *     name="api_user-create",
     *     methods={"POST"}
     * )
     * @SWG\Response(
     *     response=201,
     *     description="User created successfully"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Bad request"
     * )
     * @SWG\Response(
     *     response=401,
     *     description="Authentication failed"
     * )
     * @SWG\Response(
     *     response=409,
     *     description="Conflict"
     * )
     * @SWG\Parameter(
     *     name="user",
     *     type="object",
     *     in="body",
     *     schema=@SWG\Schema(type="object",
     *         @SWG\Property(property="email", type="string"),
     *         @SWG\Property(property="password", type="string")
     *     )
     * )
     *
     * @SWG\Tag(name="User")
     * @param Request $request
     * @return Response
     * @throws AssertionFailedException
     * @throws Throwable
     */
    public function __invoke(Request $request): Response
    {
        $email = $request->get('email');
        $plainPassword = $request->get('password');

        Assertion::notNull($email, "Email can\'t be null");
        Assertion::notNull($plainPassword, "Password can\'t be null");

        $command = new SignUpCommand($email, $plainPassword);
        $this->exec($command);

        return new JsonResponse(null, Response::HTTP_CREATED);
    }
}
