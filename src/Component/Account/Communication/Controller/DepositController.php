<?php declare(strict_types=1);

namespace App\Component\Account\Communication\Controller;

use App\Component\Account\Business\AccountBusinessFacade;
use App\Component\Account\Business\Form\DepositFormType;
use App\Component\Account\Business\Validation\Collection\AccountValidationException;
use App\Component\User\Business\UserBusinessFacade;
use App\DTO\TransactionValueObject;
use App\Entity\DepositValue;
use App\Symfony\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DepositController extends AbstractController
{
    public function __construct(
        private readonly AccountBusinessFacade $accountBusinessFacade,
        private readonly UserBusinessFacade    $userBusinessFacade,
    )
    {
    }

    #[Route('/deposit/submit', name: 'deposit_submit', methods: ['POST'])]
    public function submit(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $token = $request->headers->get('Authorization');
        $activeUser = $this->userBusinessFacade->getUserFromToken($token);

        $depositValue = new DepositValue();
        $form = $this->createForm(DepositFormType::class, $depositValue);
        $form->submit($data);

        if ($form->isValid()) {
            $value = $this->accountBusinessFacade->transformInput($depositValue->getValue());

            try {
                $this->accountBusinessFacade->validate($value, $activeUser->getId());
            } catch (AccountValidationException $e) {
                return new JsonResponse([
                    'error' => $e,
                ]);
            }

            $transactionValueObject = new TransactionValueObject(
                value: $value, userId: $activeUser->getId()
            );
            $this->accountBusinessFacade->saveDeposit($transactionValueObject);
        }
        return new JsonResponse([
            'success' => 'Die Transaktion wurde erfolgreich gespeichert!',
        ]);
    }
}