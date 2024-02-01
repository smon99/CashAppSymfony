<?php declare(strict_types=1);

namespace App\Component\Paypal\Business\Model;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Paypal
{

    private array $urls = [
        true => [
            'auth' => 'https://api-m.sandbox.paypal.com/v1/oauth2/token',
            'order' => 'https://api-m.sandbox.paypal.com/v2/checkout/orders/',
        ],
        false => [
            'auth' => 'https://api-m.sandbox.paypal.com/v1/oauth2/token',
            'order' => 'https://api-m.sandbox.paypal.com/v2/checkout/orders/',
        ],
    ];

    private array $currentUrls;

    public function __construct(
        private PaypalCredentials     $credentials,
        private HttpClientInterface   $client,
        private ParameterBagInterface $parameterBag
    )
    {
        $this->currentUrls = $this->urls[$this->credentials->paypalTest];
        $this->credentials->paypalClientId = $this->parameterBag->get('paypal_clientID');
        $this->credentials->paypalSecret = $this->parameterBag->get('paypal_secret');
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function auth(): void
    {
        $response = $this->client->request('POST', $this->currentUrls['auth'], [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'auth_basic' => [
                $this->credentials->paypalClientId,
                $this->credentials->paypalSecret,
            ],
            'body' => [
                'grant_type' => 'client_credentials',
            ],
        ]);

        $responseBody = $response->getContent();
        $responseBodyArray = json_decode($responseBody, true);
        $this->credentials->paypalAuthToken = $responseBodyArray['access_token'];
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function createOrder(string $value): string
    {
        if ($this->credentials->paypalAuthToken === '') {
            $this->auth();
        }

        $this->credentials->paypalRequestId = uniqid(time() . 'Paypal', true);

        $response = $this->client->request('POST', $this->currentUrls['order'], [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->credentials->paypalAuthToken,
                'PayPal-Request-Id' => $this->credentials->paypalRequestId,
            ],
            'json' => [
                'purchase_units' => [
                    [
                        'amount' => [
                            'currency_code' => 'EUR',
                            'value' => $value,
                        ],
                    ],
                ],
                'intent' => 'CAPTURE',
                'payment_source' => [
                    'paypal' => [
                        'experience_context' => [
                            'brand_name' => 'CashApp',
                            'shipping_preference' => 'NO_SHIPPING',
                            'landing_page' => 'LOGIN',
                            'user_action' => 'PAY_NOW',
                            'payment_method_preference' => 'IMMEDIATE_PAYMENT_REQUIRED',
                            'locale' => 'de-DE',
                            'return_url' => $this->credentials->returnUrl,
                            'cancel_url' => $this->credentials->cancelUrl,
                        ],
                    ],
                ],
            ],
        ]);

        $responseBody = $response->getContent();
        $responseBodyArray = json_decode($responseBody, true);
        return $responseBodyArray['links'][1]['href'];
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function captureOrder(string $token): array
    {
        if ($this->credentials->paypalAuthToken === '') {
            $this->auth();
        }

        $response = $this->client->request('POST', $this->currentUrls['order'] . $token . '/capture', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->credentials->paypalAuthToken,
                'PayPal-Request-Id' => $this->credentials->paypalRequestId,
                'Content-Type' => 'application/json',
            ],
        ]);

        $responseBody = $response->getContent();

        $responseBodyArray = json_decode($responseBody, true);

        return [
            "captureID" => $responseBodyArray['purchase_units'][0]['payments']['captures'][0]['id'],
            "value" => $responseBodyArray['purchase_units'][0]['payments']['captures'][0]['seller_receivable_breakdown']['net_amount']['value'],
        ];
    }
}