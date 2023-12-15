<?php declare(strict_types=1);

namespace App\Tests\src\Component\User\Communication;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function testAction(): void
    {
        $client = static::createClient();
        $client->request('GET', '/registration');

        self::assertStringContainsString('Registration Controller', $client->getResponse()->getContent());
    }
}