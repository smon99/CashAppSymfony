<?php declare(strict_types=1);

namespace App\Component\User\Business\Model;

use App\Entity\AccessToken;
use App\Entity\User;
use App\Repository\AccessTokenRepository;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class AuthToken implements AccessTokenHandlerInterface
{
    public function __construct(
        private readonly AccessTokenRepository $accessTokenRepository,
    )
    {
    }

    public function getUserBadgeFrom(#[\SensitiveParameter] string $accessToken): UserBadge
    {
        $token = $this->accessTokenRepository->findOneBy(['token' => $accessToken]);

        if (!$accessToken) {
            throw new BadCredentialsException();
        }

        $this->validateToken($token);

        return new UserBadge($token->getUser()->getUserIdentifyer);
    }

    public function validateToken(AccessToken $accessToken): void
    {
        $time = new \DateTimeImmutable();

        if ($accessToken->getExpireDate() && $accessToken->getToken() < $time) {
            throw new BadCredentialsException('Token no longer valid!');
        }
    }

    public function createAccessToken(User $user): string
    {
        $time = new \DateTimeImmutable();

        $accessToken = new AccessToken();
        $accessToken->setToken('wip');
        $accessToken->setExpireDate($time->modify('+1 hour'));
        $accessToken->setUser($user);

        return $accessToken->getToken();
    }
}