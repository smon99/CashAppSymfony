<?php declare(strict_types=1);

namespace App\Component\Account\Business\Validation;

use App\Component\Account\Business\Model\Balance;

class DayValidator implements AccountValidationInterface
{
    public function __construct(private readonly Balance $balance)
    {
    }

    public function validate(float $amount, int $userID): void
    {
        $dayBalance = $this->balance->calculateBalancePerDay($userID);
        $limit = $dayBalance + $amount;

        if ($limit > 500.0) {
            throw new AccountValidationException('Tägliches Einzahlungslimit von 500€ überschritten!');
        }
    }
}