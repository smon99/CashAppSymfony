<?php declare(strict_types=1);

namespace App\Tests\src\Component\Account\Communication;

use App\DataFixtures\TransactionFixture;
use App\DataFixtures\UserFixture;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TransactionControllerTest extends WebTestCase
{
    private ?KernelBrowser $client;
    private User $user;

    private EntityManagerInterface $entityManager;

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

    protected function loadTransactionFixture(): void
    {
        (new TransactionFixture())->load($this->entityManager);
    }

    protected function loadUserFixture(): void
    {
        (new UserFixture())->load($this->entityManager);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        if (isset($_POST["logout"])) {
            unset($_POST["logout"]);
        }

        $connection = $this->entityManager->getConnection();

        $connection->executeQuery('DELETE FROM transaction');
        $connection->executeQuery('ALTER TABLE transaction AUTO_INCREMENT=0');

        $connection->executeQuery('DELETE FROM user');
        $connection->executeQuery('ALTER TABLE user AUTO_INCREMENT=0');

        $connection->close();
    }

    public function testAction(): void
    {
        $this->createAuthenticatedClient();
        $this->loadTransactionFixture();

        $this->client->request('GET', '/transaction');

        self::assertStringContainsString('Transaction Controller', $this->client->getResponse()->getContent());
    }

    public function testActionWithValidForm(): void
    {
        $this->createAuthenticatedClient();
        $this->loadTransactionFixture();

        $crawler = $this->client->request('GET', '/transaction');

        $form = $crawler->selectButton('Versenden')->form();
        $form['transaction_form[value]'] = '1';
        $form['transaction_form[receiver]'] = 'John@email.com';

        $this->client->submit($form);
        $this->client->followRedirect();

        self::assertStringContainsString('', $this->client->getResponse()->getContent());
    }

    public function testActionWithInvalidForm(): void
    {
        $this->createAuthenticatedClient();
        $this->loadTransactionFixture();

        $crawler = $this->client->request('GET', '/transaction');

        $form = $crawler->selectButton('Versenden')->form();
        $form['transaction_form[value]'] = '10000000';
        $form['transaction_form[receiver]'] = 'John@email.com';

        $this->client->submit($form);
        $this->client->followRedirect();

        self::assertStringContainsString('', $this->client->getResponse()->getContent());
    }
}