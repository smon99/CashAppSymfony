<?php declare(strict_types=1);

namespace App\Tests\src\Component\Account\Communication;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DepositControllerTest extends WebTestCase
{
    public function testAction(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@email.com');

        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/deposit');

        self::assertStringContainsString('Deposit Controller', $client->getResponse()->getContent());
    }

    public function testSubmitValidData(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@email.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/deposit');

        $form = $crawler->selectButton('Hochladen')->form();
        $form['deposit_form[value]'] = '1';
        $client->submit($form);

        $crawler = $client->followRedirect();

        self::assertStringContainsString('Die Transaktion wurde erfolgreich gespeichert!', $client->getResponse()->getContent());
    }

    public function testSubmitInvalidData(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@email.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/deposit');

        $form = $crawler->selectButton('Hochladen')->form();
        $form['deposit_form[value]'] = '51';
        $client->submit($form);

        $crawler = $client->followRedirect();

        self::assertStringContainsString('Bitte einen Betrag von mindestens 0.01€ und maximal 50€ eingeben!', $client->getResponse()->getContent());
    }

    public function testActionLogoutPath(): void
    {
        $_POST["logout"] = true;
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@email.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/deposit');

        $crawler = $client->followRedirect();

        self::assertStringContainsString('', $client->getResponse()->getContent());
        unset($_POST["logout"]);
    }

}