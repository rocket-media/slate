<?php

namespace App\Stripe;

use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Error\Base;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\Handler;

class StripeApi {

    /**
     * Charge the card
     *
     * @param  Array $paymentData  Payment data including the tokenized card
     *         data that we've gathered from Stripe on the frontend. Expected
     *         structure:
     *         [
     *              'token' => Stripe token
     *              'email' => Customer's email
     *              'description' => What product/service customer is paying for
     *              'amount' => How much to charge
     *         ]
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function charge($paymentData)
    {
        $respond = app()->make('App\Responder');
        $apiKey = app()->environment() === 'production' ? getenv('STRIPE_LIVE_SECRET_KEY') : getenv('STRIPE_TEST_SECRET_KEY');
        Stripe::setApiKey($apiKey);

        $validator = $this->makeValidator($paymentData);

        if ($validator->fails()) {
            return $respond->error('Invalid payment information', 422, ['errors' => $validator->messages()]);
        }

        // Create a charge: this will charge the user's card
        try {
            $charge = Charge::create([
                "amount" => $paymentData['amount'], // Amount in cents
                "currency" => "usd",
                "source" => $paymentData['token']['id'],
                "description" => $paymentData['description'],
                'receipt_email' => $paymentData['email']
            ]);
        } catch(Base $e) {
            // Log the exception
            $exceptionHandler = new Handler();
            $exceptionHandler->report($e);

            $statusCode = is_null($e->httpStatus) ? 400 : $e->httpStatus;

            return $respond->error($e->getMessage(), $statusCode);
        }

        return $respond->success('Success', 200, ['charge' => $charge]);
    }

    /**
     * Validates payment data.
     *
     * See expected structure in docblok for process() above
     * @param   array  $paymentData
     * @return  Illuminate\Validation\Validator
     */
    protected function makeValidator($paymentData)
    {
        $validator = Validator::make($paymentData, [
            'amount' => 'required|integer|min:1',
            'token' => 'required|array',
            'email' => 'required|email',
            'description' => 'required'
        ]);

        return $validator;
    }

}
