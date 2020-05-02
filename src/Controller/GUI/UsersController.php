<?php

declare(strict_types=1);

namespace App\Controller\GUI;

use App\Entity\Account;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    /**
     * Renders current user screen
     *
     * @Route("/", name="app_dashboard")
     */
    public function dashboard(): Response
    {
        if (!$this->getUser() instanceof Account) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('pages/dashboard.html.twig');
    }
}
