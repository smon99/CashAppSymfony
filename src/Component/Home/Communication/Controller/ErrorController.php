<?php declare(strict_types=1);

namespace App\Component\Home\Communication\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ErrorController extends AbstractController
{
    #[Route('/error', name: 'error')]
    public function action(): Response
    {
        return $this->render('error.html.twig', [
            'title' => 'Error Controller',
        ]);
    }
}