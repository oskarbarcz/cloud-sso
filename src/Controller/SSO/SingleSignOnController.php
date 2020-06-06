<?php

declare(strict_types=1);

namespace App\Controller\SSO;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SingleSignOnController extends AbstractController
{
//    private AuthenticationUtils $authenticationUtils;
//
//    public function __construct(AuthenticationUtils $authenticationUtils)
//    {
//        $this->authenticationUtils = $authenticationUtils;
//    }
//
//    /**
//     * @Route("/sso/login", name="sso_login")
//     * @param Request $request
//     * @return Response
//     */
//    public function login(Request $request): Response
//    {
//        if (!$request->get('furtherRedirect')) {
//            dd('RedirectKeyNotSetCorrectly');
//        }
//
//        $error = $this->authenticationUtils->getLastAuthenticationError();
//        $lastUsername = $this->authenticationUtils->getLastUsername();
//
//        return $this->render(
//            'pages/login.html.twig',
//            [
//                'last_username' => $lastUsername,
//                'error' => $error,
//                'csrf_key' => 'sso_auth',
//            ]
//        );
//    }
//
//    /**
//     * @Route("/sso/logout", name="sso_logout")
//     */
//    public function logout()
//    {
//    }
}
