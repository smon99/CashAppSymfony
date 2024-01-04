<?php declare(strict_types=1);

namespace App\Component\Account\Business\Validation;


use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class AccountValidation
{

    public function __construct(
        #[TaggedIterator('account_validation')]
        private readonly iterable $validators,
    )
    {
    }

    public function collectErrors(float $amount, int $userID): void
    {
        $firstError = null;

        foreach ($this->validators as $validator) {
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