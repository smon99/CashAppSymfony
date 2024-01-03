<?php declare(strict_types=1);

namespace App\Component\User\Business\Validation;

class PasswordValidator implements UserValidationInterface
{
    public function validate(string $username, string $email, string $password): void
    {

    }
}