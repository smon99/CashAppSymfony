<?php declare(strict_types=1);

namespace App\Tests\src\Component\User\Business;

use App\Component\User\Business\Model\SetupUser;
use App\Component\User\Business\UserBusinessFacade;
use App\Component\User\Persistence\Mapper\UserMapper;
use App\Component\User\Persistence\UserEntityManager;
use App\Component\User\Persistence\UserRepository;
use App\DTO\UserDTO;
use PHPUnit\Framework\TestCase;

class UserBusinessFacadeTest extends TestCase
{
    private SetupUser $setupUser;
    private UserEntityManager $userEntityManager;
    private UserRepository $userRepository;
    private UserBusinessFacade $userBusinessFacade;
    private UserMapper $userMapper;

    protected function setUp(): void
    {
        //Mocks
        $this->userEntityManager = $this->createMock(UserEntityManager::class);
        $this->userRepository = $this->createMock(UserRepository::class);

        //Dependency
        $this->setupUser = new SetupUser();
        $this->userMapper = new UserMapper();

        //Main testing-subject
        $this->userBusinessFacade = new UserBusinessFacade(
            $this->setupUser,
            $this->userEntityManager,
            $this->userMapper,
            $this->userRepository,
        );
    }

    public function testValidate(): void
    {
        $userDTO = new UserDTO();
        self::assertTrue($this->userBusinessFacade->validate($userDTO));
    }

    public function testPrepareUser(): void
    {
        $username = 'Tester';
        $email = 'Tester@Tester.de';
        $password = 'Tester123#';

        self::assertSame('Tester', $this->userBusinessFacade->prepareUser($username, $email, $password)->username);
    }

    public function testSaveUser(): void
    {
        $newUser = new UserDTO();
        $newUser->username = 'Tester';
        $newUser->email = 'Tester@Tester.de';
        $newUser->password = 'Tester123#';

        $this->userEntityManager
            ->expects(self::once())
            ->method('create');

        $this->userBusinessFacade->saveUser($newUser);
    }
}