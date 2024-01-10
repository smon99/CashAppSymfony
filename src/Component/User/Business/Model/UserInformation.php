<?php declare(strict_types=1);

namespace App\Component\User\Business\Model;

use App\Component\User\Persistence\UserRepository;
use App\DTO\UserDTO;
use Symfony\Bundle\SecurityBundle\Security;

class UserInformation
{
    public function __construct(
        private readonly Security       $security,
        private readonly UserRepository $userRepository,
    )
    {
    }

    public function findAndMap(): ?UserDTO
    {
        $email = $this->security->getUser()->getUserIdentifier();
        return $this->userRepository->byEmail($email);
    }

    public function sessionUsername(): string
    {
        return $this->findAndMap()->getUsername();
    }

    public function sessionUserID(): int
    {
        return $this->findAndMap()->getId();
    }

    public function loginStatus(): bool
    {
        return $this->findAndMap() !== null;
    }

    public function userByMail(string $email): ?UserDTO
    {
        return $this->userRepository->byEmail($email);
    }

    public function userByUsername(string $username): ?UserDTO
    {
        return $this->userRepository->byUsername($username);
    }
}