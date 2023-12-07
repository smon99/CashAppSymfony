<?php declare(strict_types=1);

namespace App\Tests\src\Component\Account\Business\Model;

use App\Component\Account\Business\Model\Balance;
use App\Component\Account\Persistence\TransactionRepository;
use App\DTO\AccountDTO;
use PHPUnit\Framework\TestCase;

class BalanceTest extends TestCase
{
    private Balance $balance;
    private TransactionRepository $transactionRepository;

    protected function setUp(): void
    {
        $this->transactionRepository = $this->createMock(TransactionRepository::class);
        $this->balance = new Balance($this->transactionRepository);
    }

    public function testCalculateBalance(): void
    {
        $transaction1 = new AccountDTO();
        $transaction2 = new AccountDTO();

        $transaction1->transactionID = 1;
        $transaction1->userID = 1;
        $transaction1->value = 4.0;
        $transaction1->createdAt = new \DateTime();
        $transaction1->purpose = 'testing';

        $transaction2->transactionID = 2;
        $transaction2->userID = 1;
        $transaction2->value = 6.0;
        $transaction2->createdAt = new \DateTime();
        $transaction2->purpose = 'testing';

        $this->transactionRepository
            ->expects(self::once())
            ->method('byUserID')
            ->willReturn([
                $transaction1,
                $transaction2,
            ]);

        self::assertSame(10.0, $this->balance->calculateBalance(1));
    }

    public function testCalculateBalancePerHour(): void
    {
        $transaction1 = new AccountDTO();
        $transaction2 = new AccountDTO();

        $transaction1->transactionID = 1;
        $transaction1->userID = 1;
        $transaction1->value = 4.0;
        $transaction1->createdAt = new \DateTime();
        $transaction1->purpose = 'testing';

        $transaction2->transactionID = 2;
        $transaction2->userID = 1;
        $transaction2->value = 6.0;
        $transaction2->createdAt = new \DateTime();
        $transaction2->purpose = 'testing';

        $this->transactionRepository
            ->expects(self::once())
            ->method('byUserID')
            ->willReturn([
                $transaction1,
                $transaction2,
            ]);

        self::assertSame(10.0, $this->balance->calculateBalancePerHour(1));
    }
}