<?php declare(strict_types=1);

namespace App\Tests\src\Component\Account\Business;

use App\Component\Account\Business\SetupDeposit;
use App\DTO\AccountDTO;
use PHPUnit\Framework\TestCase;

class SetupDepositTest extends TestCase
{
    private SetupDeposit $setupDeposit;

    protected function setUp(): void
    {
        $this->setupDeposit = new SetupDeposit();
    }

    public function testSetupDeposit(): void
    {
        $value = 1.0;
        $userID = 1;

        $preparedAccountDTO = $this->setupDeposit->prepareDeposit($value, $userID);

        self::assertSame(1, $preparedAccountDTO->userID);
    }
}