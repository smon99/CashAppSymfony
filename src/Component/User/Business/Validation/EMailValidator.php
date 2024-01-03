<?php declare(strict_types=1);

namespace App\Component\User\Business\Validation;

use App\Component\User\Business\Model\UserInformation;

class EMailValidator implements UserValidationInterface
{
    public function __construct(
        private readonly UserInformation $userInformation,
    )
    {
    }

    public
    function validate(string $username, string $email, string $password): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new UserValidationException("Bitte gültige E-Mailadresse eingeben! ");
        }

        if ($email === $this->userInformation->userByMail($email)->getEmail()) {
            throw new UserValidationException("Die E-Mailadresse ist leider schon vergeben. ");
        }
    }
}