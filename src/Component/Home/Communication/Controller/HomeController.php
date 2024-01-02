<?php declare(strict_types=1);

namespace App\Component\Home\Communication\Controller;

use App\Component\Home\Business\HomeBusinessFacade;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct(private readonly HomeBusinessFacade $homeBusinessFacade)
    {
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $activeUser = $this->getUser()->getUserIdentifier();
        return $this->render('feature.html.twig', [
            'activeUser' => $activeUser,
        ]);
    }
}