<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    #[Route('/login')]
    public function action(): Response
    {
        return $this->render('login.html.twig', [
            'title' => 'Login Controller',
        ]);
    }
}