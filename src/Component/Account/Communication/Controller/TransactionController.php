<?php declare(strict_types=1);

namespace App\Component\Account\Communication\Controller;

use App\Component\Account\Business\AccountBusinessFacade;
use App\Entity\TransactionReceiverValue;
use App\Form\TransactionFormType;
use App\Symfony\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Component\Account\Business\Validation\AccountValidationException;

class TransactionController extends AbstractController
{
    public function __construct(private readonly AccountBusinessFacade $accountFacade)
    {
    }

    #[Route('/transaction', name: 'transaction')]
    public function action(Request $request): Response
    {
        $balance = $this->accountFacade->calculateBalance($this->getLoggedInUser()->getId());
        $transactionValue = new TransactionReceiverValue();
        $form = $this->createForm(TransactionFormType::class, $transactionValue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $receiver = $this->accountFacade->findByMail($transactionValue->getReceiver());
            $validateThis = $this->accountFacade->transformInput((string)$transactionValue->getValue());
            $balance = $this->accountFacade->calculateBalance($this->getLoggedInUser()->getId());
            $error = null;

            try {
                $this->accountFacade->validate($validateThis, $this->getLoggedInUser()->getId());
            } catch (AccountValidationException $e) {
                $error = $e->getMessage();
            }

            if ($receiver === null) {
                $error = "Empfänger existiert nicht! ";
            }

            if ($validateThis > $balance) {
                $error = "Guthaben zu gering! ";
            }

            if ($error === null) {
                $senderDTO = $this->accountFacade->findByUsername($this->getLoggedInUser()->getUsername());
                $receiverDTO = $receiver;
                $transaction = $this->accountFacade->prepareTransaction($validateThis, $senderDTO, $receiverDTO);

                $this->accountFacade->saveDeposit($transaction["sender"]);
                $this->accountFacade->saveDeposit($transaction["receiver"]);

                return $this->redirectToRoute('transaction', ['success' => "Die Transaction wurde erfolgreich durchgeführt!"]);
            }

            if ($error) {
                return $this->redirectToRoute('transaction', ['error' => $error]);
            }
        }

        return $this->render('transaction.html.twig', [
            'title' => 'Transaction Controller',
            'balance' => $balance,
            'loginStatus' => $this->accountFacade->getLoginStatus(),
            'form' => $form->createView(),
        ]);
    }
}