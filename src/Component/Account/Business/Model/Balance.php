<?php declare(strict_types=1);

namespace App\Component\Account\Business\Model;

use App\Component\Account\Persistence\TransactionRepository;

class Balance implements BalanceInterface
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
        return $this->calculateBalancePerHTime($userID, 'PT1H');
    }

    public function calculateBalancePerDay(int $userID): float
    {
        return $this->calculateBalancePerHTime($userID, 'PT24H');
    }

    private function calculateBalancePerHTime(int $userId, string $time): float
    {
        $balance = 0.0;
        $transactions = $this->transactionRepository->byUserID($userId);

        $currentTime = new \DateTime();
        $oneDayAgo = $currentTime->sub(new \DateInterval($time));

        foreach ($transactions as $transaction) {
            if ($transaction->createdAt > $oneDayAgo) {
                $balance += $transaction->value;
            }
        }
        return $balance;
    }
}