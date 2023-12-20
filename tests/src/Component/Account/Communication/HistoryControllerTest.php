<?php declare(strict_types=1);

namespace App\Tests\src\Component\Account\Communication;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\SecurityBundle\Security;

class HistoryControllerTest extends WebTestCase
{
    public function testAction(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@email.com');

        $client->loginUser($testUser);

        $client->request('GET', '/history');

        self::assertStringContainsString('History Controller', $client->getResponse()->getContent());
    }
}