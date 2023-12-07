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
        $validatorDTO = new UserDTO();

        if (isset($_POST['register'])) {
            $validatorDTO = $this->userRegFacade->prepareUser($_POST['username'], $_POST['email'], $_POST['password']);

            if ($this->userRegFacade->validate($validatorDTO)) {
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $userDTO = $this->userRegFacade->prepareUser($validatorDTO->username, $validatorDTO->email, $password);
                $this->userRegFacade->saveUser($userDTO);
                $this->userRegFacade->redirect('/login');
            }
        }

        return $this->render('registration.html.twig', [
            'title' => 'Registration Controller',
            'tempUserName' => $validatorDTO->username,
            'tempMail' => $validatorDTO->email,
            'tempPassword' => $validatorDTO->password,
            'error' => $errors,
        ]);
    }
}