<?php declare(strict_types=1);

namespace App\Tests\src\Component\Home\Communication;


use App\DataFixtures\UserFixture;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ErrorControllerTest extends WebTestCase
{
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

    public function testAction(): void
    {
        $this->createAuthenticatedClient();

        $this->client->request('GET', '/error');

        self::assertStringContainsString('Error Controller', $this->client->getResponse()->getContent());
    }
}