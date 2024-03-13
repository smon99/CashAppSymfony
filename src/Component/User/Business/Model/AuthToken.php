<?php declare(strict_types=1);

namespace App\Component\User\Business\Model;

use App\Component\User\Persistence\AccessTokenEntityManager;
use App\Entity\AccessToken;
use App\Entity\User;
use App\Repository\AccessTokenRepository;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class AuthToken implements AccessTokenHandlerInterface
{
    public function __construct(
        private readonly AccessTokenRepository    $accessTokenRepository,
        private readonly AccessTokenEntityManager $accessTokenEntityManager,
    )
    {
    }

    public function getUserBadgeFrom(#[\SensitiveParameter] string $accessToken): UserBadge
    {
        $token = $this->accessTokenRepository->findOneBy(['token' => $accessToken]);

        if (!$token) {
            throw new BadCredentialsException();
        }

        $this->validateToken($token);

        return new UserBadge($token->getUser()->getUserIdentifier());
    }

    public function validateToken(AccessToken $accessToken): void
    {
        $time = new \DateTimeImmutable();

        if ($accessToken->getExpireDate() < $time) {
            $this->accessTokenEntityManager->deleteToken($accessToken);
            throw new BadCredentialsException('Token no longer valid!');
        }
    }

    public function deleteOutdatedTokens(): void
    {
        $results = $this->accessTokenRepository->findOutdatedTokens();

        foreach ($results as $result) {
            $this->accessTokenEntityManager->deleteToken($result);
        }
    }

    public function createAccessToken(User $user): string
    {
        $randomBytes = random_bytes(16);
        $token = bin2hex($randomBytes);

        $time = new \DateTimeImmutable();

        $accessToken = new AccessToken();
        $accessToken->setToken($token);
        $accessToken->setExpireDate($time->modify('+1 hour'));
        $accessToken->setUser($user);

        $this->accessTokenEntityManager->create($accessToken);

        return $accessToken->getToken();
    }
}
