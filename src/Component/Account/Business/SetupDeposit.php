<?php declare(strict_types=1);

namespace App\Component\Account\Business;

use App\DTO\AccountDTO;

class SetupDeposit
{
    public function prepareDeposit(float $value, int $userID): AccountDTO
    {
        $accountDTO = new AccountDTO();
        $accountDTO->userID = $userID;
        $accountDTO->purpose = "deposit";
        $accountDTO->transactionTime = date('H:i:s');
        $accountDTO->transactionDate = date('Y-m-d');
        $accountDTO->value = $value;

        return $accountDTO;
    }
}