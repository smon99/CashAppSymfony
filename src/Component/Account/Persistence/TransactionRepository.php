<?php declare(strict_types=1);

namespace App\Component\Account\Persistence;

use App\Component\Account\Persistence\Mapper\TransactionMapper;
use App\DTO\TransactionDTO;

class TransactionRepository
{
    public function __construct(
        private readonly \App\Repository\TransactionRepository $transactionRepository,
        private readonly TransactionMapper                     $transactionMapper,
    )
    {
    }

    /**
     * @param int $id
     * @return TransactionDTO[]
     */
    public function byUserID(int $id): array
    {
        $userTransactions = [];
        $matches = $this->transactionRepository->findBy(['userID' => $id]);

        foreach ($matches as $entry) {
            $userTransactions[] = $this->transactionMapper->entityToDto($entry);
        }

        return $userTransactions;
    }

}