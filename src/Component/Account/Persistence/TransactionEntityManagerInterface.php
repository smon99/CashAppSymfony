<?php declare(strict_types=1);

namespace App\Component\Account\Persistence;

use App\DTO\TransactionDTO;
use App\DTO\TransactionValueObject;

interface TransactionEntityManagerInterface
{
    public function create(TransactionDTO|TransactionValueObject $accountDTO): void;
}