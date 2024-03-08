<?php declare(strict_types=1);

namespace App\Component\Home\Communication\Controller;

use App\Component\Account\Business\AccountBusinessFacade;
use App\Symfony\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly AccountBusinessFacade $accountBusinessFacade
    )
    {
    }

    #[Route('/', name: 'app_home')]
    public function index(): JsonResponse
    {
        $activeUser = $this->getLoggedInUser();

        return new JsonResponse([
            'username' => $activeUser->getUsername(),
            'balance' => $this->accountBusinessFacade->calculateBalance($activeUser->getId()),
            'transactions' => $this->accountBusinessFacade->transactionsPerUserID($activeUser->getId()),
        ]);
    }
}