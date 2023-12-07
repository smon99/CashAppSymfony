<?php declare(strict_types=1);

namespace App\Component\Account\Business\Model;

use App\DTO\TransactionDTO;

class SetupDeposit
{
    public function prepareDeposit(float $value, int $userID): TransactionDTO
    {
        $accountDTO = new TransactionDTO();
        $accountDTO->userID = $userID;
        $accountDTO->purpose = "deposit";
        $accountDTO->createdAt = new \DateTime();
        $accountDTO->value = $value;

        return $accountDTO;
    }
}