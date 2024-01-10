<?php declare(strict_types=1);

namespace App\Component\Account\Business\Model;

use App\DTO\TransactionDTO;
use App\DTO\UserDTO;

class SetupTransaction
{
    public function prepareTransaction(float $value, UserDTO $userDTO, UserDTO $receiverDTO): array
    {
        $sender = new TransactionDTO();
        $receiver = new TransactionDTO();

        $sender->userID = $userDTO->id;
        $sender->purpose = 'Geldtransfer an ' . $receiverDTO->username;
        $sender->createdAt = new \DateTime();
        $sender->value = $value * (-1);

        $receiver->userID = $receiverDTO->id;
        $receiver->purpose = 'Zahlung erhalten von ' . $userDTO->username;
        $receiver->createdAt = new \DateTime();
        $receiver->value = $value;

        return [
            "sender" => $sender,
            "receiver" => $receiver,
        ];
    }
}