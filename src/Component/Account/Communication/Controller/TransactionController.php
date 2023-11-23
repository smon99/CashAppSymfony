<?php declare(strict_types=1);

namespace App\Component\Account\Communication\Controller;

use App\Component\Account\Business\AccountFacade;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends AbstractController
{
    public function __construct(private AccountFacade $accountFacade)
    {
    }

    #[Route('/transaction')]
    public function action(): Response
    {
        $activeUser = null;
        $balance = null;
        $success = null;
        $error = null;

        if (!$this->accountFacade->getLoginStatus()) {
            $this->accountFacade->redirect('/login');
        }

        if (isset($_POST["logout"])) {
            $this->accountFacade->performLogout();
            $this->accountFacade->redirect('/login');
        }

        if (isset($_POST["transfer"])) {
            $receiver = $this->accountFacade->findByMail($_POST["receiver"]);
            $validateThis = $this->accountFacade->transformInput($_POST["amount"]);
            $balance = $this->accountFacade->calculateBalance();

            $this->accountFacade->validate($validateThis, $this->accountFacade->getSessionUserID());

            if ($receiver === null) {
                $error = "Empfänger existiert nicht! ";
            }

            if ($validateThis > $balance) {
                $error = "Guthaben zu gering! ";
            }

            if ($error === null) {
                $senderDTO = $this->accountFacade->findByUsername($this->accountFacade->getSessionUsername());
                $receiverDTO = $receiver;
                $transaction = $this->accountFacade->prepareTransaction($validateThis, $senderDTO, $receiverDTO);

                $this->accountFacade->saveDeposit($transaction["sender"]);
                $this->accountFacade->saveDeposit($transaction["receiver"]);

                $success = "Die Transaction wurde erfolgreich durchgeführt!";
            }
        }

        if ($this->accountFacade->getLoginStatus()) {
            $activeUser = $this->accountFacade->getSessionUsername();
            $balance = $this->accountFacade->calculateBalance();
        }

        return $this->render('transaction.html.twig', [
            'title' => 'Transaction Controller',
            'balance' => $balance,
            'loginStatus' => $this->accountFacade->getLoginStatus(),
            'activeUser' => $activeUser,
            'success' => $success,
            'error' => $error,
        ]);
    }
}