<?php declare(strict_types=1);

namespace App\Component\Account\Communication\Controller;

use App\Component\Account\Business\AccountFacade;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HistoryController extends AbstractController
{
    public function __construct(private AccountFacade $accountFacade)
    {
    }

    #[Route('/history')]
    public function action(): Response
    {
        $transactions = null;

        if (!$this->accountFacade->getLoginStatus()) {
            $this->accountFacade->redirect('/login');
        } else {
            $transactions = $this->accountFacade->transactionsPerUserID($this->accountFacade->getSessionUserID());
        }

        return $this->render('history.html.twig', [
            'title' => 'History Controller',
            'transactions' => $transactions,
        ]);
    }
}