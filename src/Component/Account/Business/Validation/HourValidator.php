<?php declare(strict_types=1);

namespace App\Component\Account\Business\Validation;

use App\Component\Account\Business\Model\Balance;

class HourValidator implements AccountValidationInterface
{
    public function __construct(private readonly Balance $balance)
    {
    }

    public function validate(float $amount, int $userID): void
    {
        $hourBalance = $this->balance->calculateBalancePerHour($userID);
        $limit = $hourBalance + $amount;

        if ($limit > 100.0) {
            throw new AccountValidationException('Stündliches Einzahlungslimit von 100€ überschritten!');
        }
    }
}