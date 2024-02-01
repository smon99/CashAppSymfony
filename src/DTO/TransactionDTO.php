<?php declare(strict_types=1);

namespace App\DTO;

class TransactionDTO
{
    public int $transactionID = 0;
    public ?float $value = null;
    public int $userId = 0;
    public string $purpose = '';
    public ?\DateTime $createdAt;
    public ?string $paypalOrderId = null;
    public ?string $paypalPayerId = null;

    public function getValue(): float
    {
        return $this->value;
    }

    public function setValue(float $value): void
    {
        $this->value = $value;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}
