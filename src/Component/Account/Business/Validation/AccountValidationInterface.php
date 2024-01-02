<?php declare(strict_types=1);

namespace App\Component\Account\Business\Validation;

interface AccountValidationInterface
{
    /**
     * @param float $amount
     *
     * @return void
     * @throws AccountValidationException
     */
    public function validate(float $amount, int $userID): void;
}