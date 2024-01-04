<?php declare(strict_types=1);

namespace App\Component\Account\Business\Validation;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('account_validation')]
interface AccountValidationInterface
{
    /**
     * @param float $amount
     * @param int $userID
     * @return void
     * @throws AccountValidationException
     */
    public function validate(float $amount, int $userID): void;
}