<?php declare(strict_types=1);

namespace App\Component\Home\Communication\Controller;

use App\Symfony\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExperimentalController extends AbstractController
{
    #[Route('/experimental', name: 'layout')]
    public function action(): Response
    {
        $balance = 5;

        return $this->render('layout.html.twig', ['balance' => $balance,]);
    }
}