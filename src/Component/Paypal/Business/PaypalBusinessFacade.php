<?php declare(strict_types=1);

namespace App\Component\Paypal\Business;

use App\Component\Paypal\Business\Model\Paypal;
use App\Component\Paypal\Business\Model\PaypalDeposit;
use App\Entity\User;

class PaypalBusinessFacade
{
    public function __construct(
        private readonly Paypal        $paypal,
        private readonly PaypalDeposit $paypalDeposit,
    )
    {
    }

    public function createOrder(string $value): string
    {
        return $this->paypal->createOrder($value);
    }

    public function captureOrder(string $token): array
    {
        return $this->paypal->captureOrder($token);
    }

    public function paypalDeposit(User $user, array $credentials): void
    {
        $this->paypalDeposit->create($user, $credentials);
    }
}