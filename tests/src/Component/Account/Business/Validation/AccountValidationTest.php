<?php declare(strict_types=1);

namespace App\Tests\src\Component\Account\Business\Validation;

use App\Component\Account\Business\Model\Balance;
use App\Component\Account\Business\Validation\AccountValidation;
use App\Component\Account\Business\Validation\AccountValidationException;
use App\Component\Account\Business\Validation\DayValidator;
use App\Component\Account\Business\Validation\HourValidator;
use App\Component\Account\Business\Validation\SingleValidator;
use PHPUnit\Framework\TestCase;

class AccountValidationTest extends TestCase
{
    private AccountValidation $accountValidation;
    private Balance $balance;

    protected function setUp(): void
    {
        //Mocks
        $this->balance = $this->createMock(Balance::class);

        //Main testing-subject
        $this->accountValidation = new AccountValidation(new SingleValidator(), new  HourValidator($this->balance), new DayValidator($this->balance));
    }

    public function testValidateSingle(): void
    {
        try {
            $this->accountValidation->collectErrors(501, 1);
        } catch (AccountValidationException $e) {
            $error = $e;
        }

        self::assertStringContainsString('Bitte einen Betrag von mindestens 0.01€ und maximal 50€ eingeben!', $error->getMessage());
    }
}