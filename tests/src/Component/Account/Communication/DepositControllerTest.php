<?php declare(strict_types=1);

namespace App\Tests\src\Component\Account\Communication;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DepositControllerTest extends WebTestCase
{
    public function testAction(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@email.com');

        $client->loginUser($testUser);

        $client->request('GET', '/deposit');

        self::assertStringContainsString('Deposit Controller', $client->getResponse()->getContent());
    }

    public function testActionWithInput(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@email.com');

        $client->loginUser($testUser);

        $_POST["amount"] = "1";

        $client->request('GET', '/deposit');

        self::assertStringContainsString('Deposit Controller', $client->getResponse()->getContent());
        self::assertStringContainsString('Die Transaction wurde erfolgreich gespeichert!', $client->getResponse()->getContent());
    }
}