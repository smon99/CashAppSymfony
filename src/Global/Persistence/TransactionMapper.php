<?php declare(strict_types=1);

namespace App\Global\Persistence;

use App\DTO\AccountDTO;
use App\Entity\Transaction;

class TransactionMapper
{
    public function TransactionToDTO(Transaction $transaction): AccountDTO
    {
        $accountDTO = new AccountDTO();
        $accountDTO->transactionID = $transaction->getTransactionID();
        $accountDTO->value = $transaction->getValue();
        $accountDTO->userID = $transaction->getUserID();
        $accountDTO->createdAt = $transaction->getCreatedAt();
        $accountDTO->purpose = $transaction->getPurpose();

        return $accountDTO;
    }
}