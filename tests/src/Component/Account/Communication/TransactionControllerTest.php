<?php declare(strict_types=1);

namespace App\Tests\src\Component\Account\Communication;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TransactionControllerTest extends WebTestCase
{
    public function testAction(): void
    {
        $_POST["receiver"] = "hi";
        $_POST["amount"] = "1";
        $_POST["transfer"] = true;
        $client = static::createClient();
        $client->request('GET', '/transaction');

        self::assertStringContainsString('Transaction Controller', $client->getResponse()->getContent());
    }
}