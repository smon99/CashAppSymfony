<?php declare(strict_types=1);

namespace App\Component\Paypal\Business\Model;

class PaypalCredentials
{
    public bool $paypalTest = true;
    public string $paypalSecret = '';
    public string $paypalClientId = '';
    public string $paypalRequestId = '';
    public string $paypalAuthToken = '';
    public string $returnUrl = '';
    public string $cancelUrl = '';
}