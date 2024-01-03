<?php declare(strict_types=1);

namespace App\Tests\src\Component\Account\Business\Model;

use App\Component\Account\Business\Model\SetupTransaction;
use App\DTO\UserDTO;
use PHPUnit\Framework\TestCase;

class SetupTransactionTest extends TestCase
{
    private SetupTransaction $setupTransaction;

    protected function setUp(): void
    {
        $this->setupTransaction = new SetupTransaction();
    }

    public function testSetupTransaction(): void
    {
        $value = 1.0;
        $sender = new UserDTO();
        $receiver = new UserDTO();

        $sender->username = 'Tester';
        $receiver->username = 'Tester';

        $preparedTransactionDTOs = $this->setupTransaction->prepareTransaction($value, $sender, $receiver);

        self::assertStringContainsString('Geldtransfer an ' . $receiver->username, $preparedTransactionDTOs["sender"]->purpose);
        self::assertStringContainsString('Zahlung erhalten von ' . $sender->username, $preparedTransactionDTOs["receiver"]->purpose);
        self::assertSame(-1.0, $preparedTransactionDTOs["sender"]->value);
        self::assertSame(1.0, $preparedTransactionDTOs["receiver"]->value);
    }
}