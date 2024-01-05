<?php declare(strict_types=1);

namespace App\Tests\src\Component\Account\Communication;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DepositControllerTest extends WebTestCase
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
        $this->user = $userRepository->findOneByEmail('test@email.com');
        $this->client->loginUser($this->user);
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

        $connection->close();
    }

    public function testActionDeposit(): void
    {
        $this->createAuthenticatedClient();
        $this->client->request('GET', '/deposit');

        self::assertStringContainsString('Deposit Controller', $this->client->getResponse()->getContent());
    }

    public function testSubmitValidData(): void
    {
        $this->createAuthenticatedClient();

        $crawler = $this->client->request('GET', '/deposit');

        $form = $crawler->selectButton('Hochladen')->form();
        $form['deposit_form[value]'] = '1';
        $this->client->submit($form);
        $this->client->followRedirect();

        self::assertStringContainsString('Die Transaktion wurde erfolgreich gespeichert!', $this->client->getResponse()->getContent());
    }


    /*
    public function testSubmitSingleException(): void
    {
        $crawler = $this->client->request('GET', '/deposit');

        $form = $crawler->selectButton('Hochladen')->form();
        $form['deposit_form[value]'] = '0,0001';
        $this->client->submit($form);

        $crawler = $this->client->followRedirect();

        self::assertStringContainsString('Bitte einen Betrag von mindestens 0.01€ und maximal 50€ eingeben!', $this->client->getResponse()->getContent());
    }

    public function testSubmitHourException(): void
    {
        $crawler = $this->client->request('GET', '/deposit');

        $form = $crawler->selectButton('Hochladen')->form();
        $form['deposit_form[value]'] = '49';
        $this->client->submit($form);

        $crawler = $this->client->followRedirect();

        self::assertStringContainsString('Stündliches Einzahlungslimit von 100€ überschritten!', $this->client->getResponse()->getContent());
    }

    public function testActionLogoutPath(): void
    {
        $_POST["logout"] = true;

        $crawler = $this->client->request('GET', '/deposit');

        $crawler = $this->client->followRedirect();

        self::assertStringContainsString('', $this->client->getResponse()->getContent());
    }
    */
}