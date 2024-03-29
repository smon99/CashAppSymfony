<?php declare(strict_types=1);

namespace App\Component\User\Business;

use App\Component\User\Business\Model\ModifyUser;
use App\Component\User\Business\Model\SetupUser;
use App\Component\User\Persistence\Mapper\UserMapper;
use App\Component\User\Persistence\UserEntityManager;
use App\Component\User\Persistence\UserRepository;
use App\DTO\UserDTO;
use App\Entity\User;

class UserBusinessFacade implements UserBusinessFacadeInterface
{
    public function __construct(
        private readonly SetupUser         $setupUser,
        private readonly UserEntityManager $userEntityManager,
        private readonly UserMapper        $userMapper,
        private readonly UserRepository    $userRepository,
        private readonly ModifyUser        $modifyUser,
    )
    {
    }

    public function validate(): bool
    {
        return true;
    }

    public function findByEmail(string $email): ?UserDTO
    {
        return $this->userRepository->byEmail($email);
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

    public function newUsername(User $user, string $username): void
    {
        $this->modifyUser->updateUsername($user, $username);
    }

    public function newEmail(User $user, string $email): void
    {
        $this->modifyUser->updateEmail($user, $email);
    }

    public function newPassword(User $user, string $password): void
    {
        $this->modifyUser->updatePassword($user, $password);
    }
}