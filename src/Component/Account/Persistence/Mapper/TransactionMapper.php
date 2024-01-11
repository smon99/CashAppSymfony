<?php declare(strict_types=1);

namespace App\Component\Account\Persistence\Mapper;

use App\DTO\TransactionDTO;
use App\DTO\TransactionValueObject;
use App\Entity\Transaction;

class TransactionMapper
{
    public function entityToDto(Transaction $transaction): TransactionDTO
    {
        $accountDTO = new TransactionDTO();
        $accountDTO->transactionID = $transaction->getId();
        $accountDTO->value = $transaction->getValue();
        $accountDTO->userId = $transaction->getUserID();
        $accountDTO->createdAt = $transaction->getCreatedAt();
        $accountDTO->purpose = $transaction->getPurpose();

        return $accountDTO;
    }

    public function dtoToEntity(TransactionDTO|TransactionValueObject $accountDTO): Transaction
    {
        $transaction = new Transaction();
        $transaction->setValue($accountDTO->value);
        $transaction->setUserID($accountDTO->userId);
        $transaction->setCreatedAt($accountDTO->createdAt);
        $transaction->setPurpose($accountDTO->purpose);

        return $transaction;
    }
}