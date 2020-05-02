<?php

declare(strict_types=1);

namespace App\Controller\GUI;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    /**
     * @Route("/", name="app_dashboard")
     */
    public function dashboard()
    {
        return $this->render('dashboard.html.twig');
    }
}
