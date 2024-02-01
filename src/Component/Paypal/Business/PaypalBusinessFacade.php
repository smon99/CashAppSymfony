<?php declare(strict_types=1);

namespace App\Component\Paypal\Business;

class PaypalBusinessFacade
{
    public function __construct(
        private readonly Paypal $paypal,
    )
    {
    }

    public function createOrder(string $value): string
    {
        return $this->paypal->createOrder($value);
    }

    public function captureOrder(string $token): string
    {
        return $this->paypal->captureOrder($token);
    }
}