<?php declare(strict_types=1);

namespace App\Component\Account\Business\Model;

use App\DTO\AccountDTO;

class SetupDeposit
{
    public function prepareDeposit(float $value, int $userID): AccountDTO
    {
        $accountDTO = new AccountDTO();
        $accountDTO->userID = $userID;
        $accountDTO->purpose = "deposit";
        $accountDTO->createdAt = new \DateTime();
        $accountDTO->value = $value;

        return $accountDTO;
    }
}