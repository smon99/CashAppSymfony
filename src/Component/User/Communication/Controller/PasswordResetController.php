<?php declare(strict_types=1);

namespace App\Component\User\Communication\Controller;

use App\Component\User\Business\UserBusinessFacade;
use App\Symfony\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PasswordResetController extends AbstractController
{
    public function __construct(
        private readonly UserBusinessFacade $userBusinessFacade,
    )
    {
    }

    #[Route('/resetCustom', name: 'app_reset')]
    public function action(): Response
    {
        return $this->render('reset/index.html.twig');
    }
}