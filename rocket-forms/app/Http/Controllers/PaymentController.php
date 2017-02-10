<?php namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Responder;

class PaymentController extends Controller
{

    /**
     * Process a payment.
     * @param   Request    $request  The request is expected to have a 'payment'
     *          property with structure:
     *          [
     *              'gateway' => stripe|...
     *              [gateway specific data]
     *          ]
     * @param   Responder  $respond
     * @return  \Illuminate\Http\Response
     */
    public function process(Request $request, Responder $respond)
    {
        $paymentData = $request->get('payment');

        $gateway = isset($paymentData['gateway']) ? $paymentData['gateway'] : '';

        switch ($gateway) {

            case 'stripe':
                $stripeApi = app()->make('App\Stripe\StripeApi');
                $response = $stripeApi->charge($paymentData);
                break;

            default:
                return $respond->error('Invalid payment gateway.', 400);
                break;
        }

        return $response;
    }

}
