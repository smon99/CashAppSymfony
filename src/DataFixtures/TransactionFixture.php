<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Component\User\Persistence\UserRepository;
use App\Entity\Transaction;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TransactionFixture extends Fixture
{
    public function __construct(
        private readonly UserRepository $userRepository
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        //timestamps created in normal php code are accurate. however the timestamps created by phpunit are delayed one hour
        //therefore the timestamps below are also delayed one our so i can test exceptions referring to timestamps
        $halfHourAgo = (new \DateTime())->sub(new \DateInterval('PT90M'));
        $twoHoursAgo = (new \DateTime())->sub(new \DateInterval('PT3H'));

        $userID = $this->userRepository->byEmail('test@email.com')->getUserID();

        $transaction = new Transaction();
        $transaction->setPurpose('exception testing');
        $transaction->setValue(451.0);
        $transaction->setUserID($userID);
        $transaction->setCreatedAt($twoHoursAgo);

        $transaction1 = new Transaction();
        $transaction1->setPurpose('exception testing');
        $transaction1->setValue(99.0);
        $transaction1->setUserID($userID);
        $transaction1->setCreatedAt($halfHourAgo);

        $manager->persist($transaction1);
        $manager->persist($transaction);

        $manager->flush();
    }
}
