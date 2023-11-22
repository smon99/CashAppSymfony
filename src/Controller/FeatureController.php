<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeatureController
{
    #[Route('/feature/{slug}')]
    public function action(string $slug = null): Response
    {
        return new Response('Feature Controller ' . $slug);
    }
}