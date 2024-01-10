<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Transaction;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectManager;


class TransactionFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        /** @var UserRepository $userRepository */
        $userRepository = $manager->getRepository(User::class);
        $users = $userRepository->findAll();

        $transaction = new Transaction();

        $transaction->setPurpose('exception testing');
        $transaction->setValue(399.0);
        $transaction->setUserID($users[0]->getId());
        $twoHoursAgo = (new \DateTime())->sub(new \DateInterval('PT3H'));
        $transaction->setCreatedAt($twoHoursAgo);

        $manager->persist($transaction);

        $transaction = new Transaction();

        $transaction->setPurpose('exception testing');
        $transaction->setValue(99.0);
        $transaction->setUserID($users[0]->getId());
        $halfHourAgo = (new \DateTime())->sub(new \DateInterval('PT30M'));
        $transaction->setCreatedAt($halfHourAgo);

        $manager->persist($transaction);

        $transaction = new Transaction();

        $transaction->setPurpose('exception testing');
        $transaction->setValue(399.0);
        $transaction->setUserID($users[1]->getId());
        $twoHoursAgo = (new \DateTime())->sub(new \DateInterval('PT3H'));
        $transaction->setCreatedAt($twoHoursAgo);

        $manager->persist($transaction);

        $transaction = new Transaction();

        $transaction->setPurpose('exception testing');
        $transaction->setValue(29.99);
        $transaction->setUserID($users[1]->getId());
        $halfHourAgo = (new \DateTime())->sub(new \DateInterval('PT30M'));
        $transaction->setCreatedAt($halfHourAgo);

        $manager->persist($transaction);

        $manager->flush();
    }

    public function truncate(EntityManager $manager): void
    {
        $transactionRepository = $manager->getRepository(Transaction::class);

        $transactions = $transactionRepository->findAll();
        foreach ($transactions as $transation) {
            $manager->remove($transation);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixture::class];
    }

}
