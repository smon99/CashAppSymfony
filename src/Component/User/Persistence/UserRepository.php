<?php declare(strict_types=1);

namespace App\Component\User\Persistence;

use App\Component\User\Persistence\Mapper\UserMapper;
use App\DTO\UserDTO;
use App\Entity\User;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly \App\Repository\UserRepository $userRepository,
        private readonly UserMapper                     $userMapper,
    )
    {
    }

    public function byUsername(string $username): ?UserDTO
    {
        $match = $this->userRepository->findOneBy(['username' => $username]);

        if ($match instanceof User) {
            return $this->userMapper->entityToDto($match);
        }

        return null;
    }

    public function byEmail(string $email): ?UserDTO
    {
        $match = $this->userRepository->findOneBy(['email' => $email]);

        if ($match instanceof User) {
            return $this->userMapper->entityToDto($match);
        }

        return null;
    }
}