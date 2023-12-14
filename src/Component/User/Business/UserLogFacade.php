<?php declare(strict_types=1);

namespace App\Component\User\Business;

use App\Component\User\Persistence\UserRepository;
use App\DTO\UserDTO;

class UserLogFacade
{
    public function __construct(
        private readonly UserRepository $userRepository,
    )
    {
    }

    public function findByMail(string $email): ?UserDTO
    {
        return $this->userRepository->byEmail($email);
    }

    public function login(UserDTO $userDTO, string $password): void
    {
        //do nothing yet lol
    }
}