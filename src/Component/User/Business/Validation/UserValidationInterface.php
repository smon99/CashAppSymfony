<?php declare(strict_types=1);

namespace App\Component\User\Business\Validation;

interface UserValidationInterface
{
    /**
     * @param string $username
     * @param string $email
     * @param string $password
     * @return void
     * @throws UserValidationException
     */
    public function validate(string $username, string $email, string $password): void;
}