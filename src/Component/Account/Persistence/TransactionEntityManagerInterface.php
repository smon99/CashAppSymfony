<?php
declare(strict_types=1);

namespace App\Component\Account\Persistence;

use App\DTO\TransactionDTO;

interface TransactionEntityManagerInterface
{
    public function create(TransactionDTO $accountDTO): void;
}