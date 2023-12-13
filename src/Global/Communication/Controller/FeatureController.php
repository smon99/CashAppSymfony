<?php declare(strict_types=1);

namespace App\Global\Communication\Controller;

use App\Global\Business\GlobalFacade;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeatureController extends AbstractController
{
    public function __construct(private readonly GlobalFacade $globalFacade)
    {
    }

    #[Route('/feature', name: 'feature')]
    public function action(): Response
    {
        if (!$this->globalFacade->getLoginStatus()) {
            return $this->redirectToRoute('login');
        }

        $activeUser = $this->globalFacade->getSessionUsername();

        return $this->render('feature.html.twig', [
            'title' => 'Feature Controller',
            'activeUser' => $activeUser,
        ]);
    }
}