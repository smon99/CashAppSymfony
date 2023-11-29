<?php declare(strict_types=1);

namespace App\Component\Account\Business;

use App\Component\Account\Persistence\TransactionRepository;
use App\DTO\AccountDTO;
use App\DTO\UserDTO;

class AccountFacade
{
    public function __construct(
        private InputTransformer      $inputTransformer,
        private SetupDeposit          $setupDeposit,
        private SetupTransaction      $setupTransaction,
        private Balance               $balance,
        private TransactionRepository $transactionRepository,
    )
    {
    }

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

    public function calculateBalance(int $userID): float
    {
        return $this->balance->calculateBalance($userID);
    }

    public function transactionsPerUserID(int $userID): ?array
    {
        return $this->transactionRepository->transactionByUserID($userID);
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
        return $this->inputTransformer->transformInput($input);
    }

    public function redirect(string $url): void
    {
        //do nothing yet lol
    }

    public function prepareTransaction(float $value, UserDTO $userDTO, UserDTO $receiverDTO): array
    {
        return $this->setupTransaction->prepareTransaction($value, $userDTO, $receiverDTO);
    }

    public function prepareDeposit(float $value, int $userID): AccountDTO
    {
        return $this->setupDeposit->prepareDeposit($value, $userID);
    }
}