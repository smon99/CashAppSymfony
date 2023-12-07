<?php declare(strict_types=1);

namespace App\Component\Account\Persistence;

use App\Component\Account\Persistence\Mapper\TransactionMapper;
use App\DTO\AccountDTO;
use Doctrine\ORM\EntityManagerInterface;

class TransactionEntityManager
{

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TransactionMapper      $transactionMapper,
    )
    {
    }

    public function create(AccountDTO $accountDTO): void
    {
        $transaction = $this->transactionMapper->dtoToEntity($accountDTO);
        $this->entityManager->persist($transaction);
        $this->entityManager->flush();
    }
}