<?php declare(strict_types=1);

namespace App\Component\Paypal\Business;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Paypal
{

    private array $urls = [
        true => [
            'auth' => 'https://api-m.sandbox.paypal.com/v1/oauth2/token',
            'order' => 'https://api-m.sandbox.paypal.com/v2/checkout/orders/',
            'refund' => 'https://api-m.sandbox.paypal.com/v2/payments/captures/',
        ],
        false => [
            'auth' => 'https://api-m.sandbox.paypal.com/v1/oauth2/token',
            'order' => 'https://api-m.sandbox.paypal.com/v2/checkout/orders/',
            'refund' => 'https://api-m.sandbox.paypal.com/v2/payments/captures/',
        ],
    ];

    private array $currentUrls;

    public function __construct(
        private PaypalCredentials   $credentials,
        private HttpClientInterface $client,
        private ParameterBagInterface $parameterBag
    )
    {
        $this->currentUrls = $this->urls[$this->credentials->paypalTest];
        $this->parameterBag->get('paypal_secret');
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function auth(): void
    {
        $response = $this->client->request('POST', $this->currentUrls['auth'], [
            'auth' => [
                $this->credentials->paypalClientId,
                $this->credentials->paypalSecret,
            ],
            'Content-Type' => 'application/x-www-form-urlencoded',
            'body' => 'grant_type=client_credentials',
        ]);

        $content = $response->getContent();
        $responseBodyArray = json_decode($content, true);
        $this->credentials->paypalAuthToken = $responseBodyArray['access_token'];
    }
}