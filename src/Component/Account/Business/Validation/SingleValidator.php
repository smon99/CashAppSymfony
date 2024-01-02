<?php declare(strict_types=1);

namespace App\Component\Account\Business\Validation;

class SingleValidator implements AccountValidationInterface
{
    public function validate(float $amount, int $userID): void
    {
        if ($amount < 0.01 || $amount > 50) {
            throw new AccountValidationException('Bitte einen Betrag von mindestens 0.01€ und maximal 50€ eingeben!');
        }
    }
}