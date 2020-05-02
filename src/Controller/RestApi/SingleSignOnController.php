<?php

declare(strict_types=1);

namespace App\Controller\RestApi;

use App\Repository\AccountRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SingleSignOnController extends AbstractController
{
    private JWTTokenManagerInterface $manager;
    private AccountRepository $repository;

    public function __construct(JWTTokenManagerInterface $manager, AccountRepository $repository)
    {
        $this->manager = $manager;
        $this->repository = $repository;
    }

    /**
     * @Route("/sso-login", name="sso_login")
     * @param Request $request
     * @return RedirectResponse
     */
    public function handleLogin(Request $request): RedirectResponse
    {
        // check if server is allowed
        // check if user is logged in (HOW???)
        //
        $account = $this->repository->findOneBy(['email' => 'test0@example.com']);

        $token = $this->manager->create($account);
        return new RedirectResponse("http://localhost:8080/sso-success/{$token}");
    }
}
