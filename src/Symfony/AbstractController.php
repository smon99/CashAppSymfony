<?php declare(strict_types=1);

namespace App\Symfony;

use App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;

class AbstractController extends SymfonyAbstractController
{
    public function getLoggedInUser(): User
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new \RuntimeException('user not logged in');
        }

        return $user;
    }
}