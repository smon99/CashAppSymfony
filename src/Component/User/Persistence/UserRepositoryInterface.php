<?php declare(strict_types=1);

namespace App\Component\User\Persistence;

use App\DTO\UserDTO;

interface UserRepositoryInterface
{
    public function byUsername(string $username): ?UserDTO;

    public function byEmail(string $email): ?UserDTO;
}