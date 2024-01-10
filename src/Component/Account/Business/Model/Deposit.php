<?php declare(strict_types=1);

namespace App\Component\Account\Business\Model;

use App\Component\Account\Business\Validation\AccountValidationInterface;
use App\Component\Account\Persistence\TransactionEntityManagerInterface;
use App\Component\User\Business\UserBusinessFacadeInterface;
use App\DTO\TransactionDTO;
use App\DTO\UserDTO;
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

        $transactionDtoForSender = $this->createTransactionDto($value * (-1), $userEntity->getId(), $receiverUserDto->getUsername());
        $this->transactionEntityManager->create($transactionDtoForSender);

        $transactionDtoForReceiver = $this->createTransactionDto($value, $receiverUserDto->id, $userEntity->getUsername());
        $this->transactionEntityManager->create($transactionDtoForReceiver);

    }

    private function toFloat(string $input): float
    {
        $amount = str_replace(['.', ','], ['', '.'], $input);
        return (float)$amount;
    }

    private function createTransactionDto(float $value, int $userId, string $userName): TransactionDTO
    {
        $receiver = new TransactionDTO();
        $receiver->userID = $userId;
        $receiver->purpose = $userName;
        $receiver->createdAt = new \DateTime();
        $receiver->value = $value;

        return $receiver;
    }

}