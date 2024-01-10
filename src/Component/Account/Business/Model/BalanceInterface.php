<?php
declare(strict_types=1);

namespace App\Component\Account\Business\Model;

interface BalanceInterface
{
    public function calculateBalance(int $userID): float;

    public function calculateBalancePerHour(int $userID): float;

    public function calculateBalancePerDay(int $userID): float;
}