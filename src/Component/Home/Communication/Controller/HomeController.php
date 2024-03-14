<?php declare(strict_types=1);

namespace App\Component\Home\Communication\Controller;

use App\Component\Account\Business\AccountBusinessFacade;
use App\Component\User\Business\UserBusinessFacade;
use App\Symfony\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly AccountBusinessFacade $accountBusinessFacade,
        private readonly UserBusinessFacade    $userBusinessFacade,
    )
    {
    }

    #[Route('/', name: 'app_home', methods: ['POST'])]
    public function index(Request $request): JsonResponse
    {
        $token = $request->headers->get('Authorization');
        $activeUser = $this->userBusinessFacade->getUserFromToken($token);

        return $this->json([
            'username' => $activeUser->getUsername(),
            'balance' => $this->accountBusinessFacade->calculateBalance($activeUser->getId()),
        ]);
    }
}