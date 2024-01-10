<?php
declare(strict_types=1);

namespace App\Component\Account\Business\Validation;

interface AccountValidationInterface
{
    public function collectErrors(float $amount, int $userID): void;
}