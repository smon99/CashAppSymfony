<?php declare(strict_types=1);

namespace App\Component\Account\Communication\Controller;

use App\Component\Account\Business\AccountBusinessFacade;
use App\Component\User\Business\UserBusinessFacade;
use App\Symfony\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HistoryController extends AbstractController
{
    public function __construct(
        private readonly AccountBusinessFacade $accountBusinessFacade,
        private readonly UserBusinessFacade    $userBusinessFacade,
    )
    {
    }

    #[Route('/history', name: 'history')]
    public function action(Request $request): JsonResponse
    {
        $token = $request->headers->get('Authorization');
        $activeUser = $this->userBusinessFacade->getUserFromToken($token);
        $transactions = $this->accountBusinessFacade->transactionsPerUserID($activeUser->getId());

        return new JsonResponse([
            'title' => 'History Controller',
            'transactions' => $transactions,
        ]);
    }
}