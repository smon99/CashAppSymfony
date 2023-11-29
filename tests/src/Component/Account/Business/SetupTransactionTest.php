<?php declare(strict_types=1);

namespace App\Tests\src\Component\Account\Business;

use App\Component\Account\Business\SetupTransaction;
use App\DTO\AccountDTO;
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

        $preparedTransactionDTOs = $this->setupTransaction->prepareTransaction($value, $sender, $receiver);

        self::assertSame(1.0, $preparedTransactionDTOs["receiver"]->value);
    }
}