<?php declare(strict_types=1);

namespace App\Tests\Integration\Component\User\Persistence;

use App\Component\User\Persistence\Mapper\UserMapper;
use App\Component\User\Persistence\UserRepository;
use App\DataFixtures\UserFixture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private \App\Repository\UserRepository $appUserRepository;
    private UserMapper $userMapper;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $container = static::getContainer();

        $this->entityManager = $container->get(EntityManagerInterface::class);

        $this->loadUserFixture();

        $this->appUserRepository = $container->get(\App\Repository\UserRepository::class);
        $this->userMapper = new UserMapper();

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

    public function testByEmail(): void
    {
        $userRepository = new UserRepository(
            $this->appUserRepository,
            $this->userMapper,
        );

        self::assertSame('john', $userRepository->byEmail('john@email.com')->getUsername());
        self::assertNull($userRepository->byEmail('bogus'));
    }

    public function testByUsername(): void
    {
        $userRepository = new UserRepository(
            $this->appUserRepository,
            $this->userMapper,
        );

        self::assertSame('john', $userRepository->byUsername('john')->getUsername());
        self::assertNull($userRepository->byUsername('bogus'));
    }
}