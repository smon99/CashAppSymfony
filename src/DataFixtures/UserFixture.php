<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('test@email.com');
        $user->setUsername('testUser');
        $user->setPassword('Oaschloch');

        $manager->persist($user);

        $user = new User();
        $user->setEmail('john@email.com');
        $user->setUsername('john');
        $user->setPassword('Oaschloch');

        $manager->persist($user);

        $manager->flush();
    }

    public function truncate(EntityManager $manager): void
    {
        $manager->getConnection()->executeStatement('TRUNCATE TABLE user;');
    }
}
