<?php

declare(strict_types=1);

namespace App\Controller\GUI;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

        return $this->render('pages/password-recovery.html.twig');
    }
}
