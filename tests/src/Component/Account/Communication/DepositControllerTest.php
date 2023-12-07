<?php declare(strict_types=1);

namespace App\Tests\src\Component\Account\Communication;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DepositControllerTest extends WebTestCase
{
    public function testAction(): void
    {
        $client = static::createClient();
        $client->request('GET', '/deposit');

        self::assertStringContainsString('Deposit Controller', $client->getResponse()->getContent());
    }

    public function testActionWithInput(): void
    {
        $_POST["amount"] = "1";
        $client = static::createClient();
        $client->request('GET', '/deposit');

        self::assertStringContainsString('Deposit Controller', $client->getResponse()->getContent());
        self::assertStringContainsString('Die Transaction wurde erfolgreich gespeichert!', $client->getResponse()->getContent());
    }
}