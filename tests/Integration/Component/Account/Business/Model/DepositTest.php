<?php declare(strict_types=1);

namespace App\Tests\Integarion\Component\Account\Business\Model;

use App\Component\Account\Business\Model\Deposit;
use App\Component\Account\Business\Validation\Collection\AccountValidationException;
use App\Component\Account\Persistence\TransactionRepository;
use App\DataFixtures\TransactionFixture;
use App\DataFixtures\UserFixture;
use App\DTO\TransactionDTO;
use App\Entity\TransactionReceiverValue;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DepositTest extends KernelTestCase
{
    private Deposit|null $deposit;
    private TransactionRepository|null $transactionRepository;
    private User $userOne;
    private User $userTwo;
    private EntityManagerInterface $entityManager;


    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $container = static::getContainer();

        $this->entityManager = $container->get(EntityManagerInterface::class);

        $this->loadUserFixture();
        $this->loadTransactionFixture();

        $this->deposit = $container->get(Deposit::class);
        $this->transactionRepository = $container->get(TransactionRepository::class);

        /** @var UserRepository $userRepository */
        $userRepository = $container->get(UserRepository::class);
        $this->userOne = $userRepository->findOneBy(['email' => 'test@email.com']);
        $this->userTwo = $userRepository->findOneBy(['email' => 'john@email.com']);
    }

    protected function tearDown(): void
    {
        $entityManager = static::getContainer()
            ->get(EntityManagerInterface::class);

        $transactionFixture = new TransactionFixture();
        $transactionFixture->truncate($entityManager);

        $entityManager = null;

        parent::tearDown();
    }

    protected function loadTransactionFixture(): void
    {
        (new TransactionFixture())->load($this->entityManager);
    }

    protected function loadUserFixture(): void
    {
        (new UserFixture())->load($this->entityManager);
    }

    public function testCreatDeposit(): void
    {
        $transactionReceiverValue = new TransactionReceiverValue();
        $transactionReceiverValue->setReceiver($this->userOne->getEmail());
        $transactionReceiverValue->setValue('9,98');

        $this->deposit->create($transactionReceiverValue, $this->userTwo);

        $lastTransaction = $this->getLastTransactionByUser($this->userOne->getId());

        self::assertSame(9.98, $lastTransaction->value);
        self::assertSame($this->userTwo->getUsername(), $lastTransaction->purpose);
        self::assertLessThanOrEqual((new \DateTime())->getTimestamp(), $lastTransaction->createdAt->getTimestamp());

        $lastTransaction = $this->getLastTransactionByUser($this->userTwo->getId());

        self::assertSame(-9.98, $lastTransaction->value);
        self::assertSame($this->userOne->getUsername(), $lastTransaction->purpose);
        self::assertLessThanOrEqual((new \DateTime())->getTimestamp(), $lastTransaction->createdAt->getTimestamp());
    }

    public function testCreatDepositWithExceptionWhenSingleLimitExceeded(): void
    {
        $this->expectException(AccountValidationException::class);
        $this->expectExceptionMessage('Bitte einen Betrag von mindestens 0.01€ und maximal 50€ eingeben!');

        $transactionReceiverValue = new TransactionReceiverValue();
        $transactionReceiverValue->setReceiver($this->userOne->getEmail());
        $transactionReceiverValue->setValue('0,0000001');

        $this->deposit->create($transactionReceiverValue, $this->userTwo);
    }

    private function getLastTransactionByUser(int $userId): TransactionDTO
    {
        $transactions = $this->transactionRepository->byUserID($userId);
        return end($transactions);
    }
}