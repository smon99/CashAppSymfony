<?php declare(strict_types=1);

namespace App\Component\User\Communication\Controller;

use App\Component\User\Business\UserBusinessFacade;
use App\Symfony\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserSettingsController extends AbstractController
{
    public function __construct(
        private readonly UserBusinessFacade $userBusinessFacade,
    )
    {
    }

    #[Route('/user/settings', name: 'app_user_settings')]
    public function index(): Response
    {
        $user = $this->getLoggedInUser();

        return $this->render('user_settings/index.html.twig', [
            'controller_name' => 'UserSettingsController',
        ]);
    }

    #[Route('/user/username', name: 'app_user_username')]
    public function changeUsername(): Response
    {
        if (isset($_POST['new_username'])) {
            $this->userBusinessFacade->newUsername($this->getLoggedInUser(), $_POST['new_username']);
            return $this->redirectToRoute('app_user_settings');
        }
        return $this->render('user_settings/username.html.twig', []);
    }

    #[Route('/user/email', name: 'app_user_email')]
    public function changeEmail(): Response
    {
        if (isset($_POST['new_email'])) {
            $this->userBusinessFacade->newEmail($this->getLoggedInUser(), $_POST['new_email']);
            return $this->redirectToRoute('app_user_settings');
        }
        return $this->render('user_settings/email.html.twig', []);
    }

    #[Route('/user/password', name: 'app_user_password')]
    public function changePassword(): Response
    {
        if (isset($_POST['new_password'])) {
            $this->userBusinessFacade->newPassword($this->getLoggedInUser(), $_POST['new_password']);
            return $this->redirectToRoute('app_user_settings');
        }
        return $this->render('user_settings/password.html.twig', []);
    }
}
