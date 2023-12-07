<?php declare(strict_types=1);

namespace App\Tests\src\Component\Account\Communication;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HistoryControllerTest extends WebTestCase
{
    public function testAction(): void
    {
        $client = static::createClient();
        $client->request('GET', '/history');

        self::assertStringContainsString('History Controller', $client->getResponse()->getContent());
    }
}