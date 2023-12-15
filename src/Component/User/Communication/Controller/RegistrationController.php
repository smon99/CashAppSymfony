<?php declare(strict_types=1);

namespace App\Component\User\Communication\Controller;

use App\Component\User\Business\UserBusinessFacade;
use App\Component\User\Business\UserRegFacade;
use App\Form\UserDTOType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    public function __construct(private readonly UserBusinessFacade $userBusinessFacade)
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
            $plainPassword = $this->userBusinessFacade->toEntity($userFormData);
            $password = $passwordHasher->hashPassword($plainPassword, $plainPassword->getPassword());
            $save = $this->userBusinessFacade->prepareUser($userFormData->username, $userFormData->email, $password);
            $this->userBusinessFacade->saveUser($save);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration.html.twig', [
            'title' => 'Registration Controller',
            'error' => $errors,
            'form' => $form,
        ]);
    }
}