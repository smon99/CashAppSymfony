<?php declare(strict_types=1);

namespace App\DTO;

class UserDTO
{
    public int $id = 1;
    public string $username = '';
    public string $email = '';
    public string $password = '';

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getId(): int
    {
        return $this->id;
    }
}