<?php declare(strict_types=1);

namespace App\DTO;

class TransactionValueObject
{
    public readonly ?\DateTime $createdAt;

    public function __construct(
        public readonly float   $value,
        public readonly int     $userId,
        public readonly string  $purpose = 'deposit',
        ?\DateTime              $createdAt = null,
        public readonly int     $transactionID = 0,
        public readonly ?string $paypalOrderId = null,
        public readonly ?string $paypalPayerId = null,
    )
    {
        if ($createdAt === null) {
            $createdAt = new \DateTime();
        }
        $this->createdAt = $createdAt;
    }
}
