<?php declare(strict_types=1);

namespace App\Tests\src\Component\Account\Persistence;

use App\Component\Account\Persistence\Mapper\TransactionMapper;
use App\Component\Account\Persistence\TransactionEntityManager;
use App\Component\Account\Persistence\TransactionRepository;
use App\DTO\TransactionDTO;
use App\Entity\Transaction;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertSame;

class TransactionEntityManagerTest extends TestCase
{
    private TransactionEntityManager $transactionEntityManager;
    private EntityManager $entityManager;
    private TransactionMapper $transactionMapper;
    private TransactionRepository $transactionRepository;

    protected function setUp(): void
    {
        //Mocks
        $this->entityManager = $this->createMock(EntityManager::class);
        $this->transactionRepository = $this->createMock(TransactionRepository::class);
        $this->transactionMapper = $this->createMock(TransactionMapper::class);

        //Main testing-subject
        $this->transactionEntityManager = new TransactionEntityManager(
            $this->entityManager,
            $this->transactionMapper,
        );
    }

    public function testCreate(): void
    {
        $transactionDTO = new TransactionDTO();
        $transaction = new Transaction();

        $this->transactionMapper
            ->expects(self::once())
            ->method('dtoToEntity')
            ->willReturn($transaction);

        $this->transactionEntityManager->create($transactionDTO);
    }
}