<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HistoryController
{
    #[Route('/history')]
    public function action(): Response
    {
        return new Response('History Controller');
    }
}