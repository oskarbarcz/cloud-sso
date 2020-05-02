<?php

declare(strict_types=1);

namespace App\Controller\RestApi;

use App\Entity\Account;
use App\Repository\AccountRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use function dd;

class SingleSignOnController extends AbstractController
{
    private JWTTokenManagerInterface $manager;
    private AccountRepository $repository;
    private UserPasswordEncoderInterface $userPasswordEncoder;

    public function __construct(
        JWTTokenManagerInterface $manager,
        AccountRepository $repository,
        UserPasswordEncoderInterface $userPasswordEncoder
    ) {
        $this->manager = $manager;
        $this->repository = $repository;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * @Route("/sso", name="sso_login")
     * @param Request $request
     * @return Response
     */
    public function handleLogin(Request $request): Response
    {
        // check if server is allowed
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        if (!$email || !$password) {
            return $this->render('pages/login.html.twig', ['last_username' => null, 'error' => null]);
        }

        $account = $this->repository->findOneBy(['email' => $email]);

        if (!$account instanceof Account) {
            dd("Account with this email was't found: {$email}");
        }

        if (!$this->userPasswordEncoder->isPasswordValid($account, $password)) {
            dd("Auth data incorrect, tried: {$email} with password: {$password}");
        }

        $token = $this->manager->create($account);
        $url = "{$request->get('furtherRedirect')}?token={$token}";
        return $this->redirect($url);
    }


}
