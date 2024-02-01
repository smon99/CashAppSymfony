<?php declare(strict_types=1);

namespace App\Component\Paypal\Business\Model;

use App\Component\Account\Persistence\TransactionEntityManagerInterface;
use App\DTO\TransactionValueObject;
use App\Entity\User;

class PaypalDeposit
{
    public function __construct(
        private readonly TransactionEntityManagerInterface $transactionEntityManager,
    )
    {
    }

    public function create(User $user, array $credentials): void
    {
        $value = (float)$credentials["value"]; //muss float sein
        $paypalOrderId = $credentials["token"];
        $paypalPayerId = $credentials["payer"];

        $transactionValueObject = new TransactionValueObject(value: $value, userId: $user->getId(), purpose: 'PayPal Deposit', paypalOrderId: $paypalOrderId, paypalPayerId: $paypalPayerId);

        $this->transactionEntityManager->create($transactionValueObject);
    }
}