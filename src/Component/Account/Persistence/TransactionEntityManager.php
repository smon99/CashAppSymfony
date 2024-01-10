<?php declare(strict_types=1);

namespace App\Component\Account\Persistence;

use App\Component\Account\Persistence\Mapper\TransactionMapper;
use App\DTO\TransactionDTO;
use Doctrine\ORM\EntityManagerInterface;

class TransactionEntityManager implements TransactionEntityManagerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TransactionMapper      $transactionMapper,
    )
    {
    }

    public function create(TransactionDTO $accountDTO): void
    {
        $transaction = $this->transactionMapper->dtoToEntity($accountDTO);
        $this->entityManager->persist($transaction);
        $this->entityManager->flush();
    }
}