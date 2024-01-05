<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Transaction;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class TransactionFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //timestamps created in normal php code are accurate. however the timestamps created by phpunit are delayed one hour
        //therefore the timestamps below are also delayed one our so i can test exceptions referring to timestamps
        $halfHourAgo = (new \DateTime())->sub(new \DateInterval('PT30M'));
        $twoHoursAgo = (new \DateTime())->sub(new \DateInterval('PT3H'));

        $transaction = new Transaction();
        $transaction->setPurpose('exception testing');
        $transaction->setValue(399.0);
        $transaction->setUserID(1);
        $transaction->setCreatedAt($twoHoursAgo);

        $transaction1 = new Transaction();
        $transaction1->setPurpose('exception testing');
        $transaction1->setValue(99.0);
        $transaction1->setUserID(1);
        $transaction1->setCreatedAt($halfHourAgo);

        $manager->persist($transaction1);
        $manager->persist($transaction);

        $manager->flush();
    }
}
