<?php declare(strict_types=1);

namespace App\Component\Account\Persistence;

class TransactionRepository
{
    public function __construct(private readonly \App\Repository\TransactionRepository $transactionRepository)
    {
    }

    public function transactionByUserID(int $id): ?array
    {
        return $this->transactionRepository->findBy(['userID' => $id]);
    }

}