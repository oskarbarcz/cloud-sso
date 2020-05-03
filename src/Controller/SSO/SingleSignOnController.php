<?php

declare(strict_types=1);

namespace App\Controller\SSO;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SingleSignOnController extends AbstractController
{
    /**
     * @Route("/sso/login", name="sso_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'pages/login.html.twig',
            [
                'last_username' => $lastUsername,
                'error' => $error,
                'csrf_key' => 'sso_auth',
            ]
        );
    }

    /**
     * @Route("/sso/logout", name="sso_logout")
     */
    public function logout()
    {
    }
}
