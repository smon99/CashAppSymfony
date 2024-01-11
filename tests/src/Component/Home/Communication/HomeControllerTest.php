<?php declare(strict_types=1);

namespace App\Tests\src\Component\Home\Communication;

use App\DataFixtures\UserFixture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

class HomeControllerTest extends WebTestCase
{
    private EntityManagerInterface $entityManager;

    protected function tearDown(): void
    {
        parent::tearDown();

        if (isset($_POST["logout"])) {
            unset($_POST["logout"]);
        }

        $connection = $this->entityManager->getConnection();

        $connection->executeQuery('DELETE FROM user');
        $connection->executeQuery('ALTER TABLE user AUTO_INCREMENT=0');

        $connection->close();
    }

    public function createAuthenticatedClient(): void
    {
        $this->client = static::createClient();
        $container = $this->client->getContainer();
        $userRepository = $container->get(UserRepository::class);
        $this->entityManager = $container->get(EntityManagerInterface::class);

        $this->loadUserFixture();

        $this->user = $userRepository->findOneByEmail('test@email.com');
        $this->client->loginUser($this->user);
    }

    protected function loadUserFixture(): void
    {
        (new UserFixture())->load($this->entityManager);
    }

    public function testIndex(): void
    {
        $this->createAuthenticatedClient();

        $this->client->request('GET', '/');

        self::assertStringContainsString('Cash App', $this->client->getResponse()->getContent());
    }
}