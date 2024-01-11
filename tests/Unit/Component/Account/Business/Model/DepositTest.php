<?php declare(strict_types=1);

namespace App\Tests\Unit\Component\Account\Business\Model;

use App\Component\Account\Business\Model\BalanceInterface;
use App\Component\Account\Business\Model\Deposit;
use App\Component\Account\Business\Validation\AccountValidationInterface;
use App\Component\Account\Persistence\TransactionEntityManagerInterface;
use App\Component\User\Business\UserBusinessFacadeInterface;
use App\DTO\TransactionDTO;
use App\DTO\TransactionValueObject;
use App\DTO\UserDTO;
use App\Entity\TransactionReceiverValue;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class DepositTest extends TestCase
{
    public function testCreateDeposit(): void
    {
        $transactionEntityManagerStub = new class implements TransactionEntityManagerInterface {
            /**
             * @var TransactionDTO[]|TransactionValueObject[]
             */
            public array $transactionDto = [];

            public function create(TransactionDTO|TransactionValueObject $accountDTO): void
            {
                $this->transactionDto[] = $accountDTO;
            }
        };

        $receiverUserDto = $this->getReceiverUserDto();
        $deposit = new Deposit(
            $this->getUserBusinessFacadeStub($receiverUserDto),
            $this->getBalanceStub(1213.46),
            $this->createAccountValidationMock(),
            $transactionEntityManagerStub
        );

        $userEntity = $this->getUserEntity();
        $deposit->create($this->getTransactionReceiverValue('1.213,46'), $userEntity);

        $transactionDto = $transactionEntityManagerStub->transactionDto;

        self::assertCount(2, $transactionDto);

        self::assertSame(-1213.46, $transactionDto[0]->value);
        self::assertSame($userEntity->getId(), $transactionDto[0]->userId);
        self::assertSame($receiverUserDto->getUsername(), $transactionDto[0]->purpose);
        self::assertLessThanOrEqual((new \DateTime())->getTimestamp(), $transactionDto[0]->createdAt->getTimestamp());

        self::assertSame(1213.46, $transactionDto[1]->value);
        self::assertSame($receiverUserDto->getId(), $transactionDto[1]->userId);
        self::assertSame($userEntity->getUsername(), $transactionDto[1]->purpose);
    }

    public function testCreateDepositWithExceptionWhenBalanceTooSmall(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Guthaben zu gering!');

        $balanceValue = 1012.00;
        $senderValue = '1.012.01';

        $deposit = new Deposit(
            $this->getUserBusinessFacadeStub($this->getReceiverUserDto()),
            $this->getBalanceStub($balanceValue),
            $this->createStub(AccountValidationInterface::class),
            $this->createStub(TransactionEntityManagerInterface::class)
        );

        $deposit->create($this->getTransactionReceiverValue($senderValue), $this->getUserEntity());
    }

    private function getBalanceStub(float $balanceValue): BalanceInterface|\PHPUnit\Framework\MockObject\Stub
    {
        $balanceStub = $this->createStub(BalanceInterface::class);
        $balanceStub->method('calculateBalance')
            ->willReturn($balanceValue);
        return $balanceStub;
    }

    private function getUserBusinessFacadeStub(UserDTO $receiverUserDto): UserBusinessFacadeInterface|\PHPUnit\Framework\MockObject\Stub
    {
        $userBusinessFacadeStub = $this->createStub(UserBusinessFacadeInterface::class);
        $userBusinessFacadeStub->method('findByEmail')
            ->willReturn($receiverUserDto);
        return $userBusinessFacadeStub;
    }

    private function createAccountValidationMock(): AccountValidationInterface|\PHPUnit\Framework\MockObject\MockObject
    {
        $accountValidationMock = $this->createMock(AccountValidationInterface::class);
        $accountValidationMock->expects($this->once())
            ->method('collectErrors');
        return $accountValidationMock;
    }

    private function getReceiverUserDto(): UserDTO
    {
        $receiverUserDto = new UserDTO();
        $receiverUserDto->id = 23;
        $receiverUserDto->username = 'John Doe';
        return $receiverUserDto;
    }

    private function getTransactionReceiverValue(string $value): TransactionReceiverValue
    {
        $transactionReceiverValue = new TransactionReceiverValue();
        $transactionReceiverValue->setReceiver('John Doe');
        $transactionReceiverValue->setValue($value);
        return $transactionReceiverValue;
    }

    public function getUserEntity(): User
    {
        $userEntity = new User();
        $userEntity->setId(46);
        $userEntity->setUsername('Unit Test');
        return $userEntity;
    }

}