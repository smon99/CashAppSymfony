<?php declare(strict_types=1);

namespace App\Component\Account\Communication\Controller;

use App\Component\Account\Business\AccountBusinessFacade;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DepositController extends AbstractController
{
    public function __construct(private readonly AccountBusinessFacade $accountBusinessFacade)
    {
    }

    #[Route('/deposit/amount', name: 'deposit_amount', methods: ['POST'])]
    public function amount(Request $request): RedirectResponse
    {
        $input = $request->get('amount');

        $validateThis = $this->accountBusinessFacade->transformInput($input);

        $this->accountBusinessFacade->validate($validateThis, $this->accountBusinessFacade->getSessionUserID());
        $amount = $validateThis;

        $save = $this->accountBusinessFacade->prepareDeposit($amount, $this->accountBusinessFacade->getSessionUserID());
        $this->accountBusinessFacade->saveDeposit($save);

        return new RedirectResponse('/deposit?success=amount');
    }

    #[Route('/deposit', name: 'deposit')]
    public function action(Request $request): Response
    {
        $activeUser = null;
        $balance = null;
        $error = null;

        $success = $request->get('success');
        if ($success === 'amount') {
            $success = "Die Transaction wurde erfolgreich gespeichert!";
        }

        if (!$this->accountBusinessFacade->getLoginStatus()) {
            $this->accountBusinessFacade->redirect('/login');
            return $this->redirectToRoute('app_component_userlog_communication_login_action');
        }

        if (isset($_POST["logout"])) {
            $this->accountBusinessFacade->performLogout();
            $this->accountBusinessFacade->redirect('/login');
        }

        $input = $_POST["amount"] ?? null;

        if ($input !== null) {
            $validateThis = $this->accountBusinessFacade->transformInput($input);

            $this->accountBusinessFacade->validate($validateThis, $this->accountBusinessFacade->getSessionUserID());
            $amount = $validateThis;

            $save = $this->accountBusinessFacade->prepareDeposit($amount, $this->accountBusinessFacade->getSessionUserID());
            $this->accountBusinessFacade->saveDeposit($save);

            $success = "Die Transaction wurde erfolgreich gespeichert!";
        }

        if ($this->accountBusinessFacade->getLoginStatus()) {
            $activeUser = $this->accountBusinessFacade->getSessionUsername();
            $balance = $this->accountBusinessFacade->calculateBalance($this->accountBusinessFacade->getSessionUserID());
        }

        return $this->render('deposit.html.twig', [
            'title' => 'Deposit Controller',
            'balance' => $balance,
            'loginStatus' => $this->accountBusinessFacade->getLoginStatus(),
            'activeUser' => $activeUser,
            'success' => $success,
            'error' => $error,
        ]);
    }
}