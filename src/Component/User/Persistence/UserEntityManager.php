<?php declare(strict_types=1);

namespace App\Component\User\Persistence;

use App\Component\User\Persistence\Mapper\UserMapper;
use App\DTO\UserDTO;
use Doctrine\ORM\EntityManagerInterface;

class UserEntityManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserMapper             $userMapper,
    )
    {
    }

    public function create(UserDTO $userDTO): void
    {
        $user = $this->userMapper->dtoToEntity($userDTO);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}