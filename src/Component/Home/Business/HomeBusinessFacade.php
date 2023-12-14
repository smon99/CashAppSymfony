<?php declare(strict_types=1);

namespace App\Component\Home\Business;

use App\Component\User\Business\Model\UserInformation;

class HomeBusinessFacade
{
    public function __construct(
        private readonly UserInformation $userInformation,
    )
    {
    }

    public function getSessionUsername(): string
    {
        return $this->userInformation->sessionUsername();
    }
}