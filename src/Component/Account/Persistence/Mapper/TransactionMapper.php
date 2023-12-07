<?php declare(strict_types=1);

namespace App\Component\Account\Persistence\Mapper;

use App\DTO\AccountDTO;
use App\Entity\Transaction;

class TransactionMapper
{
    public function entityToDto(Transaction $transaction): AccountDTO
    {
        $accountDTO = new AccountDTO();
        $accountDTO->transactionID = $transaction->getId();
        $accountDTO->value = $transaction->getValue();
        $accountDTO->userID = $transaction->getUserID();
        $accountDTO->createdAt = $transaction->getCreatedAt();
        $accountDTO->purpose = $transaction->getPurpose();

        return $accountDTO;
    }

    public function dtoToEntity(AccountDTO $accountDTO): Transaction
    {
        $transaction = new Transaction();
        $transaction->setValue($accountDTO->value);
        $transaction->setUserID($accountDTO->userID);
        $transaction->setCreatedAt($accountDTO->createdAt);
        $transaction->setPurpose($accountDTO->purpose);

        return $transaction;
    }
}