<?php declare(strict_types=1);

namespace App\Component\Account\Communication\Controller;

use App\Component\Account\Business\AccountBusinessFacade;
use App\Component\Account\Business\Validation\AccountValidation;
use App\Component\Account\Business\Validation\AccountValidationException;
use App\Entity\DepositValue;
use App\Form\DepositFormType;
use App\Symfony\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class NewDepositController extends AbstractController
{
    public function __construct(
        private readonly AccountBusinessFacade $accountBusinessFacade
    )
    {
    }

    #[Route('/depositNew', name: 'depositnew')]
    public function action(Request $request): Response
    {
        $balance = $this->accountBusinessFacade->calculateBalance($this->getLoggedInUser()->getId());
        $error = $request->query->get('error');
        $success = $request->query->get('success');

        if (isset($_POST["logout"])) {
            return $this->redirectToRoute('app_logout');
        }

        $depositValue = new DepositValue();
        $form = $this->createForm(DepositFormType::class, $depositValue);

        return $this->render('new_deposit/index.html.twig', [
            'controller_name' => 'New Deposit Controller',
            'balance' => $balance,
            'error' => $error,
            'success' => $success,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/depositNew/submit', name: 'depositnew_submit', methods: ['POST'])]
    public function submit(Request $request): RedirectResponse
    {
        $depositValue = new DepositValue();
        $form = $this->createForm(DepositFormType::class, $depositValue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $value = $this->accountBusinessFacade->transformInput($depositValue->getValue());
            $activeUserID = $this->getLoggedInUser()->getId();

            try {
                $this->accountBusinessFacade->validate($value, $activeUserID);
            } catch (AccountValidationException $e) {
                $error = $e->getMessage();
                dd($error);
            }

            $saveData = $this->accountBusinessFacade->prepareDeposit($value, $activeUserID);
            $this->accountBusinessFacade->saveDeposit($saveData);

            $success = 'Die Transaktion wurde erfolgreich gespeichert!';
            return $this->redirectToRoute('depositnew', ['success' => $success]);

        }

        $error = 'Fehler! Die Transaktion wurde nicht gespeichert!';
        return $this->redirectToRoute('depositnew', ['error' => $error]);
    }
}