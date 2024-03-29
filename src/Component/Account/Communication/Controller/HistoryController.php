<?php declare(strict_types=1);

namespace App\Component\Account\Communication\Controller;

use App\Component\Account\Business\AccountBusinessFacade;
use App\Symfony\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HistoryController extends AbstractController
{
    public function __construct(private readonly AccountBusinessFacade $accountBusinessFacade)
    {
    }

    #[Route('/history', name: 'history')]
    public function action(): Response
    {
        $transactions = $this->accountBusinessFacade->transactionsPerUserID($this->getLoggedInUser()->getId());

        return $this->render('history/index.html.twig', [
            'title' => 'History Controller',
            'transactions' => $transactions,
        ]);
    }
}