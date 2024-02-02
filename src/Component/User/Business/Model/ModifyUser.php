<?php declare(strict_types=1);

namespace App\Component\User\Business\Model;

use App\Component\User\Persistence\UserEntityManager;
use App\Entity\User;

class ModifyUser
{
    public function __construct(
        private readonly UserEntityManager $entityManager,
    )
    {
    }

    public function updateUsername(User $user, string $newUsername): void
    {
        $user->setUsername($newUsername);
        $this->entityManager->updateUser($user);
    }

    public function updateEmail(User $user, string $newEmail): void
    {
        $user->setEmail($newEmail);
        $this->entityManager->updateUser($user);
    }

    public function updatePassword(User $user, string $newPassword): void
    {
        //do nothing yet. new password needs to be hashed

        /*
        $user->setPassword($newPassword);
        $this->entityManager->updateUser($user);
        */
    }
}