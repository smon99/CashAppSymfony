<?php declare(strict_types=1);

namespace App\Component\UserReg\Communication\Controller;

use App\Component\UserReg\Business\UserRegFacade;
use App\DTO\UserDTO;
use App\Form\UserDTOType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    public function __construct(private UserRegFacade $userRegFacade)
    {
    }

    #[Route('/registration')]
    public function action(Request $request): Response
    {
        $errors = null;

        $form = $this->createForm(UserDTOType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userFormDTO = $form->getData();
            $password = password_hash($userFormDTO->password, PASSWORD_DEFAULT);
            $save = $this->userRegFacade->prepareUser($userFormDTO->username, $userFormDTO->email, $password);
            $this->userRegFacade->saveUser($save);
            $this->userRegFacade->redirect('/login');
        }

        return $this->render('registration.html.twig', [
            'title' => 'Registration Controller',
            'error' => $errors,
            'form' => $form,
        ]);
    }
}