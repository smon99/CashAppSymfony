<?php declare(strict_types=1);

namespace App\Component\Account\Business\Validation;


class AccountValidation
{
    private array $validationCollection;

    public function __construct(AccountValidationInterface ...$validations)
    {
        $this->validationCollection = $validations;
    }

    public function collectErrors(float $amount, int $userID): void
    {
        $firstError = null;

        foreach ($this->validationCollection as $validator) {
            try {
                $validator->validate($amount, $userID);
            } catch (AccountValidationException $e) {
                if ($firstError === null) {
                    $firstError = $e;
                }
            }
        }

        if ($firstError !== null) {
            throw $firstError;
        }
    }
}