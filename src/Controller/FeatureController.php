<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeatureController extends AbstractController
{
    #[Route('/feature')]
    public function action(): Response
    {
        return $this->render('feature.html.twig', [
            'title' => 'Feature Controller',
        ]);
    }
}