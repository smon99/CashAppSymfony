<?php declare(strict_types=1);

namespace App\Component\Account\Business;

use App\Component\Account\Business\Model\Balance;
use App\Component\Account\Business\Model\InputTransformer;
use App\Component\Account\Business\Model\SetupDeposit;
use App\Component\Account\Business\Model\SetupTransaction;
use App\Component\Account\Business\Validation\AccountValidation;
use App\Component\Account\Persistence\TransactionEntityManager;
use App\Component\Account\Persistence\TransactionRepository;
use App\DTO\TransactionDTO;
use App\DTO\TransactionValueObject;
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
        private readonly AccountValidation        $accountValidation,
    )
    {
    }

    public function calculateBalance(int $userID): float
    {
        return $this->balance->calculateBalance($userID);
    }

    public function transactionsPerUserID(int $userID): ?array
    {
        return $this->transactionRepository->byUserID($userID);
    }

    public function saveDeposit(TransactionDTO|TransactionValueObject $accountDTO): void
    {
        $this->transactionEntityManager->create($accountDTO);
    }

    public function validate(float $value, int $userID): void
    {
        $this->accountValidation->collectErrors($value, $userID);
    }

    public function transformInput(string $input): float
    {
        return $this->inputTransformer->transformInput($input);
    }

    public function prepareTransaction(float $value, UserDTO $userDTO, UserDTO $receiverDTO): array
    {
        return $this->setupTransaction->prepareTransaction($value, $userDTO, $receiverDTO);
    }

    public function prepareDeposit(float $value, int $userID): TransactionDTO
    {
        return $this->setupDeposit->prepareDeposit($value, $userID);
    }
}