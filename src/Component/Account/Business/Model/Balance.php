<?php declare(strict_types=1);

namespace App\Component\Account\Business\Model;

use App\Component\Account\Persistence\TransactionRepository;

class Balance
{
    public function __construct(private readonly TransactionRepository $transactionRepository)
    {
    }

    public function calculateBalance(int $userID): float
    {
        $balance = 0.0;
        $transactions = $this->transactionRepository->byUserID($userID);

        foreach ($transactions as $transaction) {
            $balance += $transaction->value;
        }
        return $balance;
    }

    public function calculateBalancePerHour(int $userID): float
    {
        $balance = 0.0;
        $transactions = $this->transactionRepository->byUserID($userID);

        $currentTime = new \DateTime();
        $oneHourAgo = $currentTime->sub(new \DateInterval('PT1H'));

        foreach ($transactions as $transaction) {
            if ($transaction->createdAt > $oneHourAgo) {
                $balance += $transaction->value;
            }
        }
        return $balance;
    }
}