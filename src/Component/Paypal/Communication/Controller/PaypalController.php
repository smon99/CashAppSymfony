<?php declare(strict_types=1);

namespace App\Component\Paypal\Communication\Controller;

use App\Component\Paypal\Business\Paypal;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaypalController extends AbstractController
{
    public function __construct(
        private readonly Paypal $paypal,
    )
    {
    }

    #[Route('/paypal', name: 'app_paypal')]
    public function index(): Response
    {
        $this->paypal->createOrder();

        return $this->render('paypal_test/index.html.twig', [
            'controller_name' => 'Paypal Controller',
        ]);
    }
}
