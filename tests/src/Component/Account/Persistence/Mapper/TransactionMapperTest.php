<?php declare(strict_types=1);

namespace App\Tests\src\Component\Account\Persistence\Mapper;

use App\Component\Account\Persistence\Mapper\TransactionMapper;
use App\DTO\TransactionDTO;
use App\Entity\Transaction;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\DateTime;

class TransactionMapperTest extends TestCase
{
    private TransactionMapper $transactionMapper;
    private Transaction $transaction;
    private TransactionDTO $transactionDTO;

    protected function setUp(): void
    {
        //Main testing-Subject
        $this->transactionMapper = new TransactionMapper();

        //Data
        $this->transaction = new Transaction();
        $this->transaction->setValue(1.0);
        $this->transaction->setUserID(1);
        $this->transaction->setCreatedAt(new \DateTime());
        $this->transaction->setPurpose('testing');
        $this->transaction->setId(1);

        $this->transactionDTO = new TransactionDTO();
        $this->transactionDTO->transactionID = 1;
        $this->transactionDTO->value = 1.0;
        $this->transactionDTO->userId = 1;
        $this->transactionDTO->createdAt = new \DateTime();
        $this->transactionDTO->purpose = 'testing';
    }

    public function testEntityToDto(): void
    {
        $dto = $this->transactionMapper->entityToDto($this->transaction);

        self::assertSame('testing', $dto->purpose);
    }

    public function testDtoToEntity(): void
    {
        $entity = $this->transactionMapper->dtoToEntity($this->transactionDTO);

        self::assertSame(1.0, $entity->getValue());
        self::assertSame(1, $entity->getUserID());
        self::assertSame($this->transactionDTO->createdAt, $entity->getCreatedAt());
        self::assertSame('testing', $entity->getPurpose());
    }
}