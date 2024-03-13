<?php declare(strict_types=1);

namespace App\Component\User\Business;

use App\Component\User\Business\Model\AuthToken;
use App\Component\User\Business\Model\ModifyUser;
use App\Component\User\Business\Model\SetupUser;
use App\Component\User\Persistence\AccessTokenEntityManager;
use App\Component\User\Persistence\Mapper\UserMapper;
use App\Component\User\Persistence\UserEntityManager;
use App\Component\User\Persistence\UserRepository;
use App\DTO\UserDTO;
use App\Entity\AccessToken;
use App\Entity\User;
use App\Repository\AccessTokenRepository;

class UserBusinessFacade implements UserBusinessFacadeInterface
{
    public function __construct(
        private readonly SetupUser             $setupUser,
        private readonly UserEntityManager     $userEntityManager,
        private readonly UserMapper            $userMapper,
        private readonly UserRepository        $userRepository,
        private readonly ModifyUser            $modifyUser,
        private readonly AuthToken             $authToken,
        private readonly AccessTokenRepository $accessTokenRepository,
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

    public function generateAuthToken(User $user): string
    {
        return $this->authToken->createAccessToken($user);
    }

    public function getUserFromToken(string $accessToken): User
    {
        return $this->accessTokenRepository->findUserByToken($accessToken);
    }

    public function clearOutdatedTokens(): void
    {
        $this->authToken->deleteOutdatedTokens();
    }
}