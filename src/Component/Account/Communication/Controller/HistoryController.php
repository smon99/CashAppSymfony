<?php declare(strict_types=1);

namespace App\Component\Account\Communication\Controller;

use App\Component\Account\Business\AccountBusinessFacade;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        $transactions = null;

        if (!$this->accountBusinessFacade->getLoginStatus()) {
            return $this->redirectToRoute('login');
        } else {
            $transactions = $this->accountBusinessFacade->transactionsPerUserID($this->accountBusinessFacade->getSessionUserID());
        }

        return $this->render('history.html.twig', [
            'title' => 'History Controller',
            'transactions' => $transactions,
        ]);
    }
}