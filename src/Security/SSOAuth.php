<?php

declare(strict_types=1);

namespace App\Security;

use App\Repository\AccountRepository;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Guard\PasswordAuthenticatedInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

/**
 * Handles SSO login page
 */
class SSOAuth extends AbstractFormLoginAuthenticator implements PasswordAuthenticatedInterface
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'sso_login';

    private AccountRepository $accountRepository;
    private UrlGeneratorInterface $urlGenerator;
    private CsrfTokenManagerInterface $csrfTokenManager;
    private UserPasswordEncoderInterface $passwordEncoder;
    private JWTTokenManagerInterface $tokenManager;
    private RefreshTokenManagerInterface $refreshTokenManager;

    public function __construct(
        AccountRepository $accountRepository,
        UrlGeneratorInterface $urlGenerator,
        CsrfTokenManagerInterface $csrfTokenManager,
        UserPasswordEncoderInterface $passwordEncoder,
        JWTTokenManagerInterface $tokenManager,
        RefreshTokenManagerInterface $refreshTokenManager
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->tokenManager = $tokenManager;
        $this->accountRepository = $accountRepository;
        $this->refreshTokenManager = $refreshTokenManager;
    }

    /** @inheritDoc */
    public function supports(Request $request): bool
    {
        $support = self::LOGIN_ROUTE === $request->attributes->get('_route') && $request->isMethod('POST');

        if (!$support) {
            return false;
        }

        // SSO link must contain callback url
        if (!$request->get('furtherRedirect')) {
            $this->throwNotValid('SSO redirect link not accessible.');
        }

        return true;
    }

    /** @param string $message */
    private function throwNotValid(string $message = 'Given data are incorrect.'): void
    {
        throw new CustomUserMessageAuthenticationException($message);
    }

    /** @inheritDoc */
    public function getCredentials(Request $request)
    {
        $credentials = [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];

        $request->getSession()->set(Security::LAST_USERNAME, $credentials['email']);
        return $credentials;
    }

    /** @inheritDoc */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $csrfToken = new CsrfToken('sso_auth', $credentials['csrf_token']);

        if (!$this->csrfTokenManager->isTokenValid($csrfToken)) {
            throw new InvalidCsrfTokenException('CSRF token is invalid or not found.');
        }

        $user = $this->accountRepository->findOneBy(['email' => $credentials['email']]);

        if (!$user) {
            $this->throwNotValid();
        }

        return $user;
    }

    /** @inheritDoc */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        if (!$this->passwordEncoder->isPasswordValid($user, $credentials['password'])) {
            $this->throwNotValid();
        }
        return true;
    }

    /** @inheritDoc */
    public function getPassword($credentials): ?string
    {
        return $credentials['password'];
    }

    /** @inheritDoc */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $account = $token->getUser();
        $jwt = $this->tokenManager->create($account);
        $refreshToken = $this->refreshTokenManager->getLastFromUsername($account->getUsername());

        return new RedirectResponse(
            "{$request->get('furtherRedirect')}?token={$jwt}&refresh_token={$refreshToken->getRefreshToken()}"
        );
    }

    /** @inheritDoc */
    protected function getLoginUrl(): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
