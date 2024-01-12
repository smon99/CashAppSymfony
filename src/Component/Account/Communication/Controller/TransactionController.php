<?php declare(strict_types=1);

namespace App\Component\Account\Communication\Controller;

use App\Component\Account\Business\AccountBusinessFacade;
use App\Component\Account\Business\Form\TransactionFormType;
use App\Component\Account\Business\Model\Deposit;
use App\Entity\TransactionReceiverValue;
use App\Symfony\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends AbstractController
{
    public function __construct(
        private readonly AccountBusinessFacade $accountFacade,
        private readonly Deposit               $deposit,
    )
    {
    }

    #[Route('/transaction', name: 'transaction')]
    public function action(Request $request): Response
    {
        $error = $request->query->get('error');
        $success = $request->query->get('success');

        $balance = $this->accountFacade->calculateBalance($this->getLoggedInUser()->getId());
        $transactionValue = new TransactionReceiverValue();
        $form = $this->createForm(TransactionFormType::class, $transactionValue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->deposit->create($transactionValue, $this->getLoggedInUser());
            } catch (\Exception $e) {
                return $this->redirectToRoute('transaction', ['error' => $e->getMessage()]);
            }

            return $this->redirectToRoute('transaction', ['success' => "Die Transaction wurde erfolgreich durchgeführt!"]);
        }

        return $this->render('transaction.html.twig', [
            'title' => 'Transaction Controller',
            'balance' => $balance,
            'form' => $form->createView(),
            'error' => $error,
            'success' => $success,
        ]);
    }
}