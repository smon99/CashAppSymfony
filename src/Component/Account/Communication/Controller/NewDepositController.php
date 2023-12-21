<?php declare(strict_types=1);

namespace App\Component\Account\Communication\Controller;

use App\Component\Account\Business\AccountBusinessFacade;
use App\Symfony\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\TransactionDTOType;
use App\DTO\TransactionDTO;

class NewDepositController extends AbstractController
{
    public function __construct(private readonly AccountBusinessFacade $accountBusinessFacade)
    {
    }

    #[Route ('/depositNew', name: 'depositnew')]
    public function action(Request $request): Response
    {
        $balance = $this->accountBusinessFacade->calculateBalance($this->getLoggedInUser()->getId());
        $error = null;
        $success = null;
        $loginStatus = $this->accountBusinessFacade->getLoginStatus();

        if (isset($_POST["logout"])) {
            return $this->redirectToRoute('app_logout');
        }

        $transaction = new TransactionDTO();
        $form = $this->createForm(TransactionDTOType::class, $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $value = $transaction->getValue();
            $activeUserID = $this->getLoggedInUser()->getId();

            $saveData = $this->accountBusinessFacade->prepareDeposit($value, $activeUserID);
            $this->accountBusinessFacade->saveDeposit($saveData);

            $success = 'Die Transaktion wurde erfolgreich gespeichert!';
        }

        return $this->render('new_deposit/index.html.twig', [
            'controller_name' => 'Deposit Controller temp',
            'balance' => $balance,
            'error' => $error,
            'success' => $success,
            'form' => $form->createView(),
            'loginStatus' => $loginStatus,
        ]);
    }
}
