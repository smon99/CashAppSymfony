<?php declare(strict_types=1);

namespace App\Component\Account\Communication\Controller;

use App\Component\Account\Business\AccountFacade;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DepositController extends AbstractController
{
    public function __construct(private AccountFacade $accountFacade)
    {
    }

    #[Route('/deposit')]
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

        $input = $_POST["amount"] ?? null;

        if ($input !== null) {
            $validateThis = $this->accountFacade->transformInput($input);

            $this->accountFacade->validate($validateThis, $this->accountFacade->getSessionUserID());
            $amount = $validateThis;

            $save = $this->accountFacade->prepareDeposit($amount, $this->accountFacade->getSessionUserID());
            $this->accountFacade->saveDeposit($save);

            $success = "Die Transaction wurde erfolgreich gespeichert!";
        }

        if ($this->accountFacade->getLoginStatus()) {
            $activeUser = $this->accountFacade->getSessionUsername();
            $balance = $this->accountFacade->calculateBalance();
        }

        return $this->render('deposit.html.twig', [
            'title' => 'Deposit Controller',
            'balance' => $balance,
            'loginStatus' => $this->accountFacade->getLoginStatus(),
            'activeUser' => $activeUser,
            'success' => $success,
            'error' => $error,
        ]);
    }
}