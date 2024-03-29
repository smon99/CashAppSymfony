<?php declare(strict_types=1);

namespace App\Tests\Integration\Component\User\Persistence;

use App\Component\User\Persistence\Mapper\UserMapper;
use App\Component\User\Persistence\UserEntityManager;
use App\DataFixtures\UserFixture;
use App\DTO\UserDTO;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserEntityManagerTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private UserRepository $appUserRepository;
    private UserMapper $userMapper;
    private UserDTO $userDTO;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $container = static::getContainer();

        $this->entityManager = $container->get(EntityManagerInterface::class);

        $this->loadUserFixture();

        $this->appUserRepository = $container->get(\App\Repository\UserRepository::class);
        $this->userMapper = new UserMapper();

        $this->userDTO = new UserDTO();

        parent::setUp(); // TODO: Change the autogenerated stub
    }

    protected function tearDown(): void
    {
        $connection = $this->entityManager->getConnection();

        $connection->executeQuery('DELETE FROM user');
        $connection->executeQuery('ALTER TABLE user AUTO_INCREMENT=0');

        $connection->close();

        parent::tearDown();
    }

    protected function loadUserFixture(): void
    {
        (new UserFixture())->load($this->entityManager);
    }

    public function testCreate(): void
    {
        $userEntityManager = new UserEntityManager(
            $this->entityManager,
            $this->userMapper,
        );

        $userEntityManager->create($this->userDTO);

        $result = $this->appUserRepository->findOneBy(['email' => $this->userDTO->getEmail()]);

        self::assertSame($this->userDTO->getUsername(), $result->getUsername());
    }
}