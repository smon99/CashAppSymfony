<?php declare(strict_types=1);

namespace App\Component\User\Business\Model;

use App\DTO\UserDTO;

class SetupUser
{
    public function prepareUser(string $username, string $email, string $password): UserDTO
    {
        $userDTO = new UserDTO();
        $userDTO->username = $username;
        $userDTO->email = $email;
        $userDTO->password = $password;

        return $userDTO;
    }
}