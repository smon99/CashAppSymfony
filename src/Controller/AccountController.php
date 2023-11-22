<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/account')]
    public function action(): Response
    {
        return $this->render('account.html.twig', [
            'title' => 'Account Controller',
        ]);
    }
}