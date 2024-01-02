<?php declare(strict_types=1);

namespace App\Tests\src\Component\Account\Business\Validation;

use App\Component\Account\Business\Validation\AccountValidation;
use App\Component\Account\Business\Validation\AccountValidationException;
use App\Component\Account\Business\Validation\SingleValidator;
use PHPUnit\Framework\TestCase;

class AccountValidationTest extends TestCase
{
    private AccountValidation $accountValidation;

    protected function setUp(): void
    {
        $this->accountValidation = new AccountValidation(new SingleValidator());
    }

    public function testValidate(): void
    {
        try {
            $this->accountValidation->collectErrors(400, 1);
        } catch (AccountValidationException $e) {
            $error = $e;
        }

        self::assertStringContainsString('Bitte einen Betrag von mindestens 0.01€ und maximal 50€ eingeben!', $error->getMessage());
    }
}