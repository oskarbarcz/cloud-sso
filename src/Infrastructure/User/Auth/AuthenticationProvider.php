<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Auth;

use Assert\AssertionFailedException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Ramsey\Uuid\UuidInterface;

final class AuthenticationProvider
{
    private JWTTokenManagerInterface $jwtTokenManager;

    public function __construct(JWTTokenManagerInterface $jwtTokenManager)
    {
        $this->jwtTokenManager = $jwtTokenManager;
    }

    /**
     * @param UuidInterface $uuid
     * @param string        $email
     * @param string        $hashedPassword
     * @return string
     * @throws AssertionFailedException
     */
    public function generateToken(UuidInterface $uuid, string $email, string $hashedPassword): string
    {
        $auth = Auth::create($uuid, $email, $hashedPassword);

        return $this->jwtTokenManager->create($auth);
    }
}