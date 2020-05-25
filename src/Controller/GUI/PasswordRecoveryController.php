<?php

declare(strict_types=1);

namespace App\Controller\GUI;

use App\Service\PasswordRecoveryManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PasswordRecoveryController extends AbstractController
{
    private PasswordRecoveryManager $manager;

    public function __construct(PasswordRecoveryManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/account/password-recovery/start", name="app_account_password-recovery_init")
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function init(Request $request): Response
    {
        $email = $request->request->get('email');
        if (!$email) {
            return $this->render('pages/password-recovery/init.html.twig');
        }

        $this->manager->addNewToken($email);
        return $this->redirectToRoute('app_account_password-recovery_enter-code');
    }

    /**
     * @Route("/account/password-recovery/enter-code/{token}", name="app_account_password-recovery_enter-code")
     * @param Request     $request
     * @param string|null $token
     * @return Response
     */
    public function proceedWithCode(Request $request, string $token = null): Response
    {
        $tokenInRequest = $request->request->get('token', null);
        $passwords = [$request->request->get('password'), $request->request->get('retype_password')];

        // if password form submitted
        if ($passwords[0] !== null) {
            $this->manager->saveNewPassword($token, $passwords[0]);
            return $this->redirectToRoute('app_account_password-recovery_success');
        }

        // move token from req body to URL
        if ($token === null && $tokenInRequest !== null) {
            return $this->redirectToRoute('app_account_password-recovery_enter-code', ['token' => $tokenInRequest]);
        }

        if ($token !== null && $this->manager->verifyToken($token)) {
            return $this->render('pages/password-recovery/password-form.html.twig');
        }

        return $this->render('pages/password-recovery/enter-code.html.twig');
    }

    /**
     * @Route("/account/password-recovery/success", name="app_account_password-recovery_success")
     * @return Response
     */
    public function success(): Response
    {
        return $this->render('pages/password-recovery/success.html.twig');
    }
}
