<?php declare(strict_types=1);

namespace App\Component\User\Persistence;

use App\Component\User\Persistence\Mapper\UserMapper;
use App\DTO\UserDTO;
use App\Entity\User;

class UserRepository
{
    public function __construct(
        private readonly \App\Repository\UserRepository $userRepository,
        private readonly UserMapper                     $userMapper,
    )
    {
    }

    public function byUsername(string $username): UserDTO
    {
        $match = $this->userRepository->findBy(['username' => $username]);
        return $this->userMapper->entityToDto($match[0]);
    }

    public function byEmail(string $email): ?UserDTO
    {
        $match = $this->userRepository->findOneBy(['email' => $email]);

        if($match instanceof User) {
            return $this->userMapper->entityToDto($match);
        }

        return null;
    }
}