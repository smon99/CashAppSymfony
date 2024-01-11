<?php declare(strict_types=1);

namespace App\Tests\src\Component\Account\Business;

use App\Component\Account\Business\AccountBusinessFacade;
use App\Component\Account\Business\Model\Balance;
use App\Component\Account\Business\Model\InputTransformer;
use App\Component\Account\Business\Model\SetupDeposit;
use App\Component\Account\Business\Model\SetupTransaction;
use App\Component\Account\Business\Validation\AccountValidation;
use App\Component\Account\Persistence\TransactionEntityManager;
use App\Component\Account\Persistence\TransactionRepository;
use App\DTO\TransactionDTO;
use App\DTO\UserDTO;
use PHPUnit\Framework\TestCase;

class AccountBusinessFacadeTest extends TestCase
{
    private AccountBusinessFacade $accountBusinessFacade;
    private InputTransformer $inputTransformer;
    private SetupDeposit $setupDeposit;
    private SetupTransaction $setupTransaction;
    private Balance $balance;
    private TransactionRepository $transactionRepository;
    private TransactionEntityManager $transactionEntityManager;
    private AccountValidation $accountValidation;
    private UserDTO $userDTO;

    protected function setUp(): void
    {
        //Mocks
        $this->transactionRepository = $this->createMock(TransactionRepository::class);
        $this->transactionEntityManager = $this->createMock(TransactionEntityManager::class);
        $this->balance = $this->createMock(Balance::class);
        $this->accountValidation = $this->createMock(AccountValidation::class);

        //Dependency
        $this->inputTransformer = new InputTransformer();
        $this->setupDeposit = new SetupDeposit();
        $this->setupTransaction = new SetupTransaction();

        //Main testing-subject
        $this->accountBusinessFacade = new AccountBusinessFacade(
            $this->inputTransformer,
            $this->setupDeposit,
            $this->setupTransaction,
            $this->balance,
            $this->transactionRepository,
            $this->transactionEntityManager,
            $this->accountValidation
        );

        //DTO and Entity
        $this->userDTO = new UserDTO();
        $this->userDTO->setUsername('Tester');
        $this->userDTO->setEmail('Tester@Tester.de');
        $this->userDTO->setPassword('Tester123#');
        $this->userDTO->id = 1;
    }

    public function testCalculateBalance(): void
    {
        $this->balance
            ->expects(self::once())
            ->method('calculateBalance')
            ->willReturn(10.0,);

        self::assertSame(10.0, $this->accountBusinessFacade->calculateBalance(1));
    }

    public function testTransactionsPerUserID(): void
    {
        $transaction1 = new TransactionDTO();
        $transaction2 = new TransactionDTO();

        $transaction1->transactionID = 1;
        $transaction1->userId = 1;
        $transaction1->value = 4.0;
        $transaction1->createdAt = new \DateTime();
        $transaction1->purpose = 'testing';

        $transaction2->transactionID = 2;
        $transaction2->userId = 1;
        $transaction2->value = 6.0;
        $transaction2->createdAt = new \DateTime();
        $transaction2->purpose = 'testing';

        $this->transactionRepository
            ->expects(self::once())
            ->method('byUserID')
            ->willReturn([
                $transaction1,
                $transaction2,
            ]);

        self::assertSame($transaction1->transactionID, $this->accountBusinessFacade->transactionsPerUserID(1)[0]->transactionID);
    }

    public function testSaveDeposit(): void
    {
        $transaction1 = new TransactionDTO();

        $transaction1->transactionID = 1;
        $transaction1->userId = 1;
        $transaction1->value = 4.0;
        $transaction1->createdAt = new \DateTime();
        $transaction1->purpose = 'testing';

        $this->transactionEntityManager
            ->expects(self::once())
            ->method('create');

        $this->accountBusinessFacade->saveDeposit($transaction1);
    }

    public function testTransformInput(): void
    {
        self::assertSame(1.00, $this->accountBusinessFacade->transformInput('1'));
    }

    public function testPrepareTransaction(): void
    {
        $value = 5.0;
        $userDTO = new UserDTO();
        $receiverDTO = new UserDTO();

        $userDTO->id = 1;
        $userDTO->username = 'testUser';

        $receiverDTO->id = 2;
        $receiverDTO->username = 'testReceiver';

        self::assertSame(5.0, $this->accountBusinessFacade->prepareTransaction($value, $userDTO, $receiverDTO)["receiver"]->value);
    }

    public function testPrepareDeposit(): void
    {
        $value = 10.0;
        $userID = 1;

        self::assertSame(10.0, $this->accountBusinessFacade->prepareDeposit($value, $userID)->value);
    }

    public function testValidate(): void
    {
        $this->accountValidation
            ->expects(self::once())
            ->method('collectErrors');

        $this->accountBusinessFacade->validate(55.0, 1);
    }
}