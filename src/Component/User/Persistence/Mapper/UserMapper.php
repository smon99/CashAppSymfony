<?php declare(strict_types=1);

namespace App\Component\User\Persistence\Mapper;

use App\DTO\UserDTO;
use App\Entity\User;

class UserMapper
{
    public function entityToDto(User $user): UserDTO
    {
        $userDTO = new UserDTO();
        $userDTO->userID = $user->getId();
        $userDTO->username = $user->getUsername();
        $userDTO->email = $user->getEmail();
        $userDTO->password = $user->getPassword();

        return $userDTO;
    }

    public function dtoToEntity(UserDTO $userDTO): User
    {
        $user = new User();
        $user->setUsername($userDTO->username);
        $user->setEmail($userDTO->email);
        $user->setPassword($userDTO->password);

        return $user;
    }
}