<?php declare(strict_types=1);

namespace App\Component\UserReg\Communication\Controller;

use App\Component\UserReg\Business\UserRegFacade;
use App\Form\UserDTOType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    public function __construct(private readonly UserRegFacade $userRegFacade)
    {
    }

    #[Route('/registration', name: 'registration')]
    public function action(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $errors = null;

        $form = $this->createForm(UserDTOType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userFormData = $form->getData();
            $plainPassword = $this->userRegFacade->toEntity($userFormData);
            $password = $passwordHasher->hashPassword($plainPassword, $plainPassword->getPassword());
            $save = $this->userRegFacade->prepareUser($userFormData->username, $userFormData->email, $password);
            $this->userRegFacade->saveUser($save);

            return $this->redirectToRoute('login');
        }

        return $this->render('registration.html.twig', [
            'title' => 'Registration Controller',
            'error' => $errors,
            'form' => $form,
        ]);
    }
}