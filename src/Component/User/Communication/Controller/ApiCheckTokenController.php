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
    {}

    #[Route('/api/check-token', name: 'api_check_token')]
    public function checkToken(Request $request): Response
    {
        $user = null;
        $accessToken = $request->headers->get('Authorization');

        if ($accessToken !== null)
        {
            $user = $this->userBusinessFacade->getUserFromToken($accessToken);
        }

        return $this->json([
            'valid' => true,
            'user' => $user
        ]);
    }
}