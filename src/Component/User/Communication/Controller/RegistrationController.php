<?php declare(strict_types=1);

namespace App\Component\User\Communication\Controller;

use App\Component\User\Business\UserBusinessFacade;
use App\DTO\UserDTO;
use App\Form\UserDTOType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    public function __construct(
        private readonly UserBusinessFacade          $userBusinessFacade,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
    )
    {
    }

    #[Route('/registration', name: 'registration')]
    public function action(Request $request): Response
    {
        $errors = null;

        $form = $this->createForm(UserDTOType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UserDTO $userFormData */
            $userFormData = $form->getData();
            $plainPassword = $this->userBusinessFacade->toEntity($userFormData);
            $userFormData->password = $this->userPasswordHasher->hashPassword($plainPassword, $plainPassword->getPassword());
            $this->userBusinessFacade->saveUser($userFormData);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration.html.twig', [
            'title' => 'Registration Controller',
            'error' => $errors,
            'form' => $form,
        ]);
    }
}