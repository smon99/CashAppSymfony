<?php declare(strict_types=1);

namespace App\Component\Account\Business;

use App\DTO\AccountDTO;
use App\DTO\UserDTO;

class AccountFacade
{
    public function getLoginStatus(): bool
    {
        return true;
    }

    public function getSessionUsername(): string
    {
        return 'Simon';
    }

    public function getSessionUserID(): int
    {
        return 1;
    }

    public function performLogout(): void
    {
        //do nothing yet lol
    }

    public function calculateBalance(): float
    {
        return 45.00;
    }

    public function transactionsPerUserID(int $userID): array
    {
        $transaction1 = new AccountDTO();
        $transaction2 = new AccountDTO();
        $transaction3 = new AccountDTO();

        $transaction1->transactionID = 1;
        $transaction1->userID = 1;
        $transaction1->transactionDate = "vorgestern du hunt";
        $transaction1->transactionTime = "04:20";
        $transaction1->value = 10.0;
        $transaction1->purpose = "deposit";

        $transaction2->transactionID = 2;
        $transaction2->userID = 1;
        $transaction2->transactionDate = "gestern blyat";
        $transaction2->transactionTime = "69:00";
        $transaction2->value = 15.0;
        $transaction2->purpose = "deposit";

        $transaction3->transactionID = 3;
        $transaction3->userID = 1;
        $transaction3->transactionDate = "heute";
        $transaction3->transactionTime = "16:20";
        $transaction3->value = 20.0;
        $transaction3->purpose = "deposit";

        return [
            $transaction1,
            $transaction2,
            $transaction3,
        ];
    }

    public function findByMail(string $email): ?UserDTO
    {
        $userDTO = new UserDTO();

        $userDTO->userID = 2;
        $userDTO->username = "Nico";
        $userDTO->password = "geheim123!";
        $userDTO->email = "Nico@Nico.de";

        return $userDTO;
    }

    public function findByUsername(string $username): ?UserDTO
    {
        $userDTO = new UserDTO();

        $userDTO->userID = 2;
        $userDTO->username = "Nico";
        $userDTO->password = "geheim123!";
        $userDTO->email = "Nico@Nico.de";

        return $userDTO;
    }

    public function saveDeposit(AccountDTO $accountDTO): void
    {
        //do nothing yet lol
    }

    public function validate(float $value, int $userID): void
    {
        //do nothing yet lol
    }

    public function transformInput(string $input): float
    {
        return 1.0;
    }

    public function redirect(string $url): void
    {
        //do nothing yet lol
    }

    public function prepareTransaction(float $value, UserDTO $userDTO, UserDTO $receiverDTO): array
    {
        $sender = new AccountDTO();
        $receiver = new AccountDTO();

        $sender->userID = 1;
        $sender->purpose = 'Geldtransfer an' . $receiverDTO->username;
        $sender->transactionTime = "";
        $sender->transactionDate = "";
        $sender->value = $value * (-1);

        $receiver->userID = 2;
        $receiver->purpose = 'Zahlung erhalten von ' . $userDTO->username;
        $receiver->transactionTime = "";
        $receiver->transactionDate = "";
        $receiver->value = $value;

        return [
            "sender" => $sender,
            "receiver" => $receiver,
        ];
    }

    public function prepareDeposit(float $value, int $userID): AccountDTO
    {
        $accountDTO = new AccountDTO();

        $accountDTO->transactionID = 4;
        $accountDTO->userID = 1;
        $accountDTO->transactionDate = "";
        $accountDTO->transactionTime = "";
        $accountDTO->value = 1.0;
        $accountDTO->purpose = "deposit";

        return $accountDTO;
    }
}