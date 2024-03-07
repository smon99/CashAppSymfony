<?php declare(strict_types=1);

namespace App\Component\Account\Communication\Controller;

use App\Component\Account\Business\AccountBusinessFacade;
use App\Symfony\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HistoryController extends AbstractController
{
    public function __construct(private readonly AccountBusinessFacade $accountBusinessFacade)
    {
    }

    #[Route('/history', name: 'history')]
    public function action(): JsonResponse
    {
        $transactions = $this->accountBusinessFacade->transactionsPerUserID($this->getLoggedInUser()->getId());

        return new JsonResponse([
            'title' => 'History Controller',
            'transactions' => $transactions,
        ]);
    }
}