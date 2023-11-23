<?php declare(strict_types=1);

namespace App\Global\Business;

class GlobalFacade
{
    public function getLoginStatus(): bool
    {
        return true;
    }

    public function redirect(string $url): void
    {
        //do nothing yet lol
    }

    public function getSessionUsername(): string
    {
        return 'Simon';
    }
}