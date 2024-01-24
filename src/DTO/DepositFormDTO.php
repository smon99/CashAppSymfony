<?php declare(strict_types=1);

namespace App\DTO;

class DepositFormDTO
{
    public ?string $value = null;

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): void
    {
        $this->value = $value;
    }
}