<?php declare(strict_types=1);

namespace App\Tests\src\Component\Account\Communication;

use App\DataFixtures\UserFixture;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class HistoryControllerTest extends WebTestCase
{
    private EntityManagerInterface $entityManager;
    private KernelBrowser $client;

    private UserPasswordHasherInterface $userPasswordHasher;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $container = $this->client->getContainer();

        $this->userPasswordHasher = $container->get(UserPasswordHasherInterface::class);
        $this->entityManager = $container->get(EntityManagerInterface::class);
    }

    public function testAction(): void
    {

        (new UserFixture($this->userPasswordHasher))->load($this->entityManager);

        $user = new User();
        $user->setEmail('test@email.com')->setPassword('password');

        $this->client->loginUser($user);

        $this->client->request('GET', '/history');

        self::assertStringContainsString('History Controller', $this->client->getResponse()->getContent());
    }
}