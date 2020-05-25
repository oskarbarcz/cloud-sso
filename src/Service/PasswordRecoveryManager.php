<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Account;
use App\Entity\PasswordRecoveryToken as Token;
use App\Repository\AccountRepository;
use App\Repository\PasswordRecoveryTokenRepository as TokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use RuntimeException;

class PasswordRecoveryManager
{
    private AccountRepository $accountRepository;
    private TokenRepository $tokenRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        AccountRepository $accountRepository,
        TokenRepository $tokenRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->accountRepository = $accountRepository;
        $this->tokenRepository = $tokenRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * Creates password restore token
     *
     * @param string $email
     * @throws Exception
     */
    public function addNewToken(string $email): void
    {
        $account = $this->accountRepository->findOneBy(['email' => $email]);

        if (!$account instanceof Account) {
            throw new RuntimeException('User with this email does not exists.');
        }

        // remove old token if exists
        $oldToken = $account->getPasswordRecoveryToken();
        if ($oldToken instanceof Token) {
            $this->entityManager->remove($oldToken);
            $this->entityManager->flush();
        }

        $token = new Token();
        $token->setToken(RandomStringGenerator::generate(64));

        $account->setPasswordRecoveryToken($token);

        // mail should be sent here
        mail($account->getEmail(), 'Password Recovery', "token: {$token->getToken()}");

        $this->entityManager->persist($account);
        $this->entityManager->flush();
    }

    public function verifyToken()
    {
    }
}
