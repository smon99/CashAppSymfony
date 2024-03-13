<?php declare(strict_types=1);

namespace App\Component\User\Communication\Controller;

use App\Component\User\Business\UserBusinessFacade;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ApiLoginController extends AbstractController
{
    public function __construct(
        private readonly UserBusinessFacade $userBusinessFacade
    )
    {
    }

    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function index(#[CurrentUser] ?User $user): JsonResponse
    {
        if (null === $user) {
            return $this->json([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = $this->userBusinessFacade->generateAuthToken($user);
        $this->userBusinessFacade->clearOutdatedTokens();

        return $this->json([
            'user' => $user->getUsername(),
            'token' => $token,
        ]);
    }
}
