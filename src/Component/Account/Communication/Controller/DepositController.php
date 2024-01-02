<?php declare(strict_types=1);

namespace App\Component\Account\Communication\Controller;

use App\Component\Account\Business\AccountBusinessFacade;
use App\Symfony\AbstractController;
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

        $this->accountBusinessFacade->validate($validateThis, $this->getLoggedInUser()->getId());
        $amount = $validateThis;

        $save = $this->accountBusinessFacade->prepareDeposit($amount, $this->getLoggedInUser()->getId());
        $this->accountBusinessFacade->saveDeposit($save);

        return new RedirectResponse('/deposit?success=amount');
    }

    #[Route('/deposit', name: 'deposit')]
    public function action(Request $request): Response
    {
        $error = null;

        $success = $request->get('success');
        if ($success === 'amount') {
            $success = "Die Transaction wurde erfolgreich gespeichert!";
        }

        if (isset($_POST["logout"])) {
            return $this->redirectToRoute('app_logout');
        }

        $input = $_POST["amount"] ?? null;

        if ($input !== null) {
            $validateThis = $this->accountBusinessFacade->transformInput($input);

            $this->accountBusinessFacade->validate($validateThis, $this->getLoggedInUser()->getId());
            $amount = $validateThis;

            $save = $this->accountBusinessFacade->prepareDeposit($amount, $this->getLoggedInUser()->getId());
            $this->accountBusinessFacade->saveDeposit($save);
        }

        $balance = $this->accountBusinessFacade->calculateBalance($this->getLoggedInUser()->getId());

        return $this->render('deposit.html.twig', [
            'title' => 'Deposit Controller',
            'balance' => $balance,
            'loginStatus' => $this->accountBusinessFacade->getLoginStatus(),
            'success' => $success,
            'error' => $error,
        ]);
    }
}