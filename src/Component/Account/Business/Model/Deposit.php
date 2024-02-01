<?php declare(strict_types=1);

namespace App\Component\Account\Business\Model;

use App\Component\Account\Business\Validation\AccountValidationInterface;
use App\Component\Account\Persistence\TransactionEntityManagerInterface;
use App\Component\User\Business\UserBusinessFacadeInterface;
use App\DTO\TransactionValueObject;
use App\Entity\TransactionReceiverValue;
use App\Entity\User;

class Deposit
{
    public function __construct(
        private readonly UserBusinessFacadeInterface       $userBusinessFacade,
        private readonly BalanceInterface                  $balance,
        private readonly AccountValidationInterface        $accountValidation,
        private readonly TransactionEntityManagerInterface $transactionEntityManager,
    )
    {
    }

    public function create(TransactionReceiverValue $transactionReceiverValueEntity, User $userEntity): void
    {
        $receiverUserDto = $this->userBusinessFacade->findByEmail($transactionReceiverValueEntity->getReceiver());
        if ($receiverUserDto === null) {
            throw new \RuntimeException('Empfänger existiert nicht!');
        }

        $value = $this->toFloat($transactionReceiverValueEntity->getValue());
        $balance = $this->balance->calculateBalance($userEntity->getId());

        if ($value > $balance) {
            throw new \RuntimeException("Guthaben zu gering! ");
        }

        $this->accountValidation->collectErrors($value, $userEntity->getId());

        $transactionValueObjectForSender = new TransactionValueObject(value: $value * (-1), userId: $userEntity->getId(), purpose: $receiverUserDto->getUsername());
        $this->transactionEntityManager->create($transactionValueObjectForSender);

        $transactionValueObjectForReceiver = new TransactionValueObject(value: $value, userId: $receiverUserDto->id, purpose: $userEntity->getUsername());
        $this->transactionEntityManager->create($transactionValueObjectForReceiver);
    }

    private function toFloat(string $input): float
    {
        $amount = str_replace(['.', ','], ['', '.'], $input);
        return (float)$amount;
    }
}