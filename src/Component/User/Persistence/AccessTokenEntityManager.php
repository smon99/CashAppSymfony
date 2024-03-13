<?php declare(strict_types=1);

namespace App\Component\User\Persistence;

use App\Entity\AccessToken;
use Doctrine\ORM\EntityManagerInterface;

class AccessTokenEntityManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    public function create(AccessToken $accessToken): void
    {
        $this->entityManager->persist($accessToken);
        $this->entityManager->flush();
    }

    public function deleteToken(AccessToken $accessToken): void
    {
        $this->entityManager->remove($accessToken);
        $this->entityManager->flush();
    }
}