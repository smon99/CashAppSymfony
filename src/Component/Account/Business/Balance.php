<?php declare(strict_types=1);

namespace App\Component\Account\Business;

use App\Component\Account\Persistence\TransactionRepository;

class Balance
{
    public function __construct(private readonly TransactionRepository $transactionRepository)
    {
    }

    public function calculateBalance(int $userID): float
    {
        $balance = 0.0;
        $transactions = $this->transactionRepository->transactionByUserID($userID);

        foreach ($transactions as $transaction) {
            $balance += $transaction->value;
        }

        return $balance;
    }
}