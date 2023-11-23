<?php declare(strict_types=1);

namespace App\Component\UserLog\Business;

use App\DTO\UserDTO;

class UserLogFacade
{
    public function findByMail(string $email): ?UserDTO
    {
        $userDTO = new UserDTO();

        $userDTO->userID = 1;
        $userDTO->username = "Simon";
        $userDTO->email = "Simon@Simon.de";
        $userDTO->password = "Simon123!";

        return $userDTO;
    }

    public function login(UserDTO $userDTO, string $password): void
    {
        //do nothing yet lol
    }

    public function redirect(string $url): void
    {
        //do nothing yet lol
    }
}