<?php

declare(strict_types=1);

namespace App\Controller\GUI;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use function dd;

class PasswordRecoveryController extends AbstractController
{
    /**
     * @Route("/account/password-recovery/start", name="app_account_password-recovery_init")
     * @param Request $request
     * @return Response
     */
    public function init(Request $request): Response
    {
        $email = $request->request->get('email');
        if ($email) {
            dd($email);
        }

        return $this->render('pages/password-recovery/init.html.twig');
    }

    /**
     * @Route("/account/password-recovery/enter-code/{token}", name="app_account_password-recovery_enter-code")
     * @param Request $request
     * @return Response
     */
    public function proceedWithCode(Request $request, string $token = null): Response
    {
        $token = $token ?? $request->request->get('token');

        if ($token !== null) {
            dd($token);
        }

        return $this->render('pages/password-recovery/enter-code.html.twig');
    }

    /**
     * @Route("/account/password-recovery/sucess", name="app_account_password-recovery_success")
     * @param Request $request
     * @return Response
     */
    public function success(Request $request, string $token = null): Response
    {
        $token = $token ?? $request->request->get('token');

        if ($token !== null) {
            dd($token);
        }

        return $this->render('pages/password-recovery/enter-code.html.twig');
    }
}
