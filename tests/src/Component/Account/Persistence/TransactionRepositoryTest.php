<?php declare(strict_types=1);

namespace App\Tests\src\Component\Account\Persistence;

use App\Component\Account\Persistence\Mapper\TransactionMapper;
use App\Component\Account\Persistence\TransactionRepository;
use App\Entity\Transaction;
use PHPUnit\Framework\TestCase;

class TransactionRepositoryTest extends TestCase
{
    private TransactionRepository $transactionRepository;
    private TransactionMapper $transactionMapper;
    private \App\Repository\TransactionRepository $repository;
    private Transaction $transaction;

    protected function setUp(): void
    {
        //Mocks
        $this->repository = $this->createMock(\App\Repository\TransactionRepository::class);

        //Dependency
        $this->transactionMapper = new TransactionMapper();

        //Main testing-subject
        $this->transactionRepository = new TransactionRepository(
            $this->repository,
            $this->transactionMapper
        );

        //Data
        $this->transaction = new Transaction();
        $this->transaction->setValue(1.0);
        $this->transaction->setUserID(1);
        $this->transaction->setCreatedAt(new \DateTime());
        $this->transaction->setPurpose('testing');
        $this->transaction->setId(1);
    }

    public function testByUserID(): void
    {
        $this->repository
            ->expects(self::once())
            ->method('findBy')
            ->willReturn([$this->transaction]);

        $this->transactionRepository->byUserID(1);
    }
}