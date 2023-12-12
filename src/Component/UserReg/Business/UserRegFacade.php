<?php declare(strict_types=1);

namespace App\Component\UserReg\Business;

use App\Component\User\Persistence\UserEntityManager;
use App\Component\UserReg\Business\Model\SetupUser;
use App\DTO\UserDTO;

class UserRegFacade
{
    public function __construct(
        private readonly SetupUser         $setupUser,
        private readonly UserEntityManager $userEntityManager,
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

    public function redirect(string $url): void
    {
        //do nothing yet lol
    }
}