<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user')]
    public function action(): Response
    {
        return $this->render('user.html.twig', [
            'title' => 'User Controller',
        ]);
    }
}