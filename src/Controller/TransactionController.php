<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends AbstractController
{
    #[Route('/transaction')]
    public function action(): Response
    {
        return $this->render('transaction.html.twig', [
            'title' => 'Transaction Controller',
        ]);
    }
}