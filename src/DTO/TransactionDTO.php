<?php declare(strict_types=1);

namespace App\DTO;

class TransactionDTO
{
    public int $transactionID = 0;
    public float $value = 0.00;
    public int $userID = 0;
    public string $purpose = '';
    public ?\DateTime $createdAt;
}