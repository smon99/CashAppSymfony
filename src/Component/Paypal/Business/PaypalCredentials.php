<?php declare(strict_types=1);

namespace App\Component\Paypal\Business;

class PaypalCredentials
{
    public function __construct(
        public string $paypalClientId,
        public string $paypalSecret,
        public string $paypalBrandName,
        public string $successUrl,
        public string $cancelUrl,
        public string $currency,
        public string $locale,
        public bool   $paypalTest = true,
    )
    {
    }

    public string $paypalRequestId = ''; //Set automatically
    public string $paypalAuthToken = ''; //Set automatically
}