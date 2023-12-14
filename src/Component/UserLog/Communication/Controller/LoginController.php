<?php declare(strict_types=1);

namespace App\Component\UserLog\Communication\Controller;

use App\Component\UserLog\Business\UserLogFacade;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    public function __construct(private readonly UserLogFacade $userLogFacade)
    {
    }

    #[Route('/login', name: 'login')]
    public function action(): Response
    {
        $credentials = null;

        if (isset($_POST["login"])) {
            $credentials = $this->formInput();
        }

        if ($credentials !== null) {
            $userDTO = $this->userLogFacade->findByMail($credentials["mail"]);

            if ($userDTO !== null) {
                $this->userLogFacade->login($userDTO, $credentials["password"]);
                return $this->redirectToRoute('feature');
            }
        }

        return $this->render('login.html.twig', [
            'title' => 'Login Controller',
        ]);
    }

    private function formInput(): array
    {
        $mailCheck = $_POST["mail"];
        $password = $_POST["password"];

        return [
            "mail" => $mailCheck,
            "password" => $password,
        ];
    }
}