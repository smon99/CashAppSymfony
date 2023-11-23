<?php declare(strict_types=1);

namespace App\Component\UserReg\Communication\Controller;

use App\Component\UserReg\Business\UserRegFacade;
use App\DTO\UserDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    public function __construct(private UserRegFacade $userRegFacade)
    {
    }

    #[Route('/registration')]
    public function action(): Response
    {
        $errors = null;
        $userCheck = '';
        $mailCheck = '';
        $passwordCheck = '';

        if (isset($_POST['register'])) {
            $userCheck = $_POST['username'];
            $mailCheck = $_POST['email'];
            $passwordCheck = $_POST['password'];

            $validatorDTO = new UserDTO();
            $validatorDTO->username = $userCheck;
            $validatorDTO->email = $mailCheck;
            $validatorDTO->password = $passwordCheck;

            $this->userRegFacade->validate($validatorDTO);

            $password = password_hash($passwordCheck, PASSWORD_DEFAULT);

            $userDTO = new UserDTO();
            $userDTO->username = $userCheck;
            $userDTO->email = $mailCheck;
            $userDTO->password = $password;

            $this->userRegFacade->saveUser($userDTO);
            $this->userRegFacade->redirect('/login');
        }

        return $this->render('registration.html.twig', [
            'title' => 'Registration Controller',
            'tempUserName' => $userCheck,
            'tempMail' => $mailCheck,
            'tempPassword' => $passwordCheck,
            'error' => $errors,
        ]);
    }
}