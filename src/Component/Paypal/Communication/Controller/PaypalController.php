<?php declare(strict_types=1);

namespace App\Component\Paypal\Communication\Controller;

use App\Component\Paypal\Business\PaypalBusinessFacade;
use App\Symfony\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaypalController extends AbstractController
{
    public function __construct(
        private readonly PaypalBusinessFacade $paypalBusinessFacade,
    )
    {
    }

    #[Route('/paypal', name: 'app_paypal')]
    public function index(): Response
    {
        if (isset($_POST['paypalValue'])) {
            return $this->redirect($this->paypalBusinessFacade->createOrder($_POST['paypalValue']));
        }

        return $this->render('paypal/index.html.twig', [
            'controller_name' => 'Paypal Controller',
        ]);
    }

    #[Route('/paypal/success', name: 'app_paypal_success')]
    public function proceed(Request $request): Response
    {
        $token = $request->query->get('token');
        $payerId = $request->query->get('PayerID');
        $value = $this->paypalBusinessFacade->captureOrder($token)["value"];

        $credentials = [
            "token" => $token,
            "payer" => $payerId,
            "value" => $value,
        ];

        $this->paypalBusinessFacade->paypalDeposit($this->getLoggedInUser(), $credentials);

        return $this->render('paypal/index.html.twig', [
            'controller_name' => 'Paypal Controller',
        ]);
    }
}
