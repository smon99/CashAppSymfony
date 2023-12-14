<?php declare(strict_types=1);

namespace App\Tests\src\Component\UserReg\Business;

use App\Component\User\Business\Model\SetupUser;
use App\Component\User\Business\UserRegFacade;
use App\Component\User\Persistence\Mapper\UserMapper;
use App\Component\User\Persistence\UserEntityManager;
use App\DTO\UserDTO;
use PHPUnit\Framework\TestCase;

class UserRegFacadeTest extends TestCase
{
    private SetupUser $setupUser;
    private UserEntityManager $userEntityManager;
    private UserRegFacade $userRegFacade;
    private UserMapper $userMapper;

    protected function setUp(): void
    {
        //Mocks
        $this->userEntityManager = $this->createMock(UserEntityManager::class);

        //Dependency
        $this->setupUser = new SetupUser();
        $this->userMapper = new UserMapper();

        //Main testing-subject
        $this->userRegFacade = new UserRegFacade(
            $this->setupUser,
            $this->userEntityManager,
            $this->userMapper,
        );
    }

    public function testValidate(): void
    {
        $userDTO = new UserDTO();
        self::assertTrue($this->userRegFacade->validate($userDTO));
    }

    public function testPrepareUser(): void
    {
        $username = 'Tester';
        $email = 'Tester@Tester.de';
        $password = 'Tester123#';

        self::assertSame('Tester', $this->userRegFacade->prepareUser($username, $email, $password)->username);
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

        $this->userRegFacade->saveUser($newUser);
    }
}