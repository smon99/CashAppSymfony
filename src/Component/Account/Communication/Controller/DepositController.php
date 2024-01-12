<?php declare(strict_types=1);

namespace App\Component\Account\Communication\Controller;

use App\Component\Account\Business\AccountBusinessFacade;
use App\Component\Account\Business\Form\DepositFormType;
use App\Component\Account\Business\Validation\Collection\AccountValidationException;
use App\DTO\TransactionValueObject;
use App\Entity\DepositValue;
use App\Symfony\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DepositController extends AbstractController
{
    public function __construct(
        private readonly AccountBusinessFacade $accountBusinessFacade
    )
    {
    }

    #[Route('/deposit', name: 'deposit')]
    public function action(Request $request): Response
    {
        $balance = $this->accountBusinessFacade->calculateBalance($this->getLoggedInUser()->getId());
        $error = $request->query->get('error');
        $success = $request->query->get('success');

        $depositValue = new DepositValue();
        $form = $this->createForm(DepositFormType::class, $depositValue);

        return $this->render('new_deposit/index.html.twig', [
            'controller_name' => 'Deposit Controller',
            'balance' => $balance,
            'error' => $error,
            'success' => $success,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/deposit/submit', name: 'deposit_submit', methods: ['POST'])]
    public function submit(Request $request): RedirectResponse
    {
        $depositValue = new DepositValue();
        $form = $this->createForm(DepositFormType::class, $depositValue);
        $form->handleRequest($request);

        if ($form->isSubmitted() ) {
            $value = $this->accountBusinessFacade->transformInput($depositValue->getValue());
            $activeUserID = $this->getLoggedInUser()->getId();

            try {
                $this->accountBusinessFacade->validate($value, $activeUserID);
            } catch (AccountValidationException $e) {
                $error = $e->getMessage();
                return $this->redirectToRoute('deposit', ['error' => $error]);
            }
        }

        $transactionValueObject = new TransactionValueObject(
            value: $value, userId: $activeUserID
        );
        $this->accountBusinessFacade->saveDeposit($transactionValueObject);

        $success = 'Die Transaktion wurde erfolgreich gespeichert!';
        return $this->redirectToRoute('deposit', ['success' => $success]);
    }
}