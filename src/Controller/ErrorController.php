<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ErrorController
{
    #[Route('/error')]
    public function action(): Response
    {
        return new Response('Error Controller');
    }
}