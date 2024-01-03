<?php declare(strict_types=1);

namespace App\Component\User\Business\Validation;


class UserValidation
{
    private array $validationCollection;

    public function __construct(UserValidationInterface ...$validations)
    {
        $this->validationCollection = $validations;
    }

    public function collectErrors(string $username, string $email, string $password): void
    {
        $firstError = null;

        foreach ($this->validationCollection as $validator) {
            try {
                $validator->validate($username, $email, $password);
            } catch (UserValidationException $e) {
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