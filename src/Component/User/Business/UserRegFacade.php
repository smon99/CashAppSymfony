<?php declare(strict_types=1);

namespace App\Component\User\Business;

use App\Component\User\Business\Model\SetupUser;
use App\Component\User\Persistence\Mapper\UserMapper;
use App\Component\User\Persistence\UserEntityManager;
use App\DTO\UserDTO;
use App\Entity\User;

class UserRegFacade
{
    public function __construct(
        private readonly SetupUser         $setupUser,
        private readonly UserEntityManager $userEntityManager,
        private readonly UserMapper        $userMapper,
    )
    {
    }

    public function validate(UserDTO $userDTO): bool
    {
        return true;
    }

    public function prepareUser(string $username, string $email, string $password): UserDTO
    {
        return $this->setupUser->prepareUser($username, $email, $password);
    }

    public function saveUser(UserDTO $userDTO): void
    {
        $this->userEntityManager->create($userDTO);
    }

    public function toEntity(UserDTO $userDTO): User
    {
        return $this->userMapper->dtoToEntity($userDTO);
    }
}