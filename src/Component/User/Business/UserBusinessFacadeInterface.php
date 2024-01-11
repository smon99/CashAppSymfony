<?php

namespace App\Component\User\Business;

use App\DTO\UserDTO;
use App\Entity\User;

interface UserBusinessFacadeInterface
{
    public function validate(): bool;

    public function findByEmail(string $email): ?UserDTO;

    public function prepareUser(string $username, string $email, string $password): UserDTO;

    public function saveUser(UserDTO $userDTO): void;

    public function toEntity(UserDTO $userDTO): User;
}