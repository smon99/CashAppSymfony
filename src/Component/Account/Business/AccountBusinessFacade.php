<?php declare(strict_types=1);

namespace App\Component\Account\Business;

use App\Component\Account\Business\Model\Balance;
use App\Component\Account\Business\Model\InputTransformer;
use App\Component\Account\Business\Model\SetupDeposit;
use App\Component\Account\Business\Model\SetupTransaction;
use App\Component\Account\Persistence\Mapper\TransactionMapper;
use App\Component\Account\Persistence\TransactionEntityManager;
use App\Component\Account\Persistence\TransactionRepository;
use App\DTO\AccountDTO;
use App\DTO\UserDTO;

class AccountBusinessFacade
{
    public function __construct(
        private readonly InputTransformer         $inputTransformer,
        private readonly SetupDeposit             $setupDeposit,
        private readonly SetupTransaction         $setupTransaction,
        private readonly Balance                  $balance,
        private readonly TransactionRepository    $transactionRepository,
        private readonly TransactionEntityManager $transactionEntityManager,
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
        return $this->transactionRepository->byUserID($userID);
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
        $this->transactionEntityManager->create($accountDTO);
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