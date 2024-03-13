<?php declare(strict_types=1);

namespace App\Component\User\Communication\Controller;

use App\Component\User\Business\UserBusinessFacade;
use App\Symfony\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiCheckTokenController extends AbstractController
{
    public function __construct
    (
        private readonly UserBusinessFacade $userBusinessFacade
    )
    {
    }

    #[Route('/api/check-token', name: 'api_check_token')]
    public function checkToken(Request $request): Response
    {
        $user = null;
        $token = $request->headers->get('Authorization');

        if ($token !== null) {
            $user = $this->userBusinessFacade->getUserFromToken($token);
        }

        return $this->json([
            'username' => $user->getUsername(),
            'Authorization' => $token,
        ]);
    }
}