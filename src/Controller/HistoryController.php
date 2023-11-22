<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HistoryController extends AbstractController
{
    #[Route('/history')]
    public function action(): Response
    {
        return $this->render('history.html.twig', [
            'title' => 'History Controller',
        ]);
    }
}