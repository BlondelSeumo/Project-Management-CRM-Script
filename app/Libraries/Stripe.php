<?php

namespace App\Libraries;

class Stripe {

    private $stripe_config;

    public function __construct() {        
        $Payment_methods_model = model("App\Models\Payment_methods_model");

        $this->stripe_config = $Payment_methods_model->get_oneline_payment_method("stripe");

        require_once(APPPATH . "ThirdParty/Stripe/vendor/autoload.php");
    }

    public function get_stripe_payment_intent_session($data = array(), $login_user = 0) {
        $invoice_id = get_array_value($data, "invoice_id");
        $currency = get_array_value($data, "currency");
        $payment_amount = get_array_value($data, "payment_amount");
        $description = get_array_value($data, "description");
        $verification_code = get_array_value($data, "verification_code");
        $contact_user_id = $login_user ? $login_user : get_array_value($data, "contact_user_id");
        $client_id = get_array_value($data, "client_id");
        $payment_method_id = get_array_value($data, "payment_method_id");
        $balance_due = get_array_value($data, "balance_due");

        if (!$invoice_id) {
            return false;
        }

        //validate public invoice information
        if (!$login_user && !validate_invoice_verification_code($verification_code, array("invoice_id" => $invoice_id, "client_id" => $client_id, "contact_id" => $contact_user_id))) {
            return false;
        }

        //check if partial payment allowed or not
        if (get_setting("allow_partial_invoice_payment_from_clients")) {
            $payment_amount = unformat_currency($payment_amount);
        } else {
            $payment_amount = $balance_due;
        }

        //validate payment amount
        if ($payment_amount < $this->stripe_config->minimum_payment_amount * 1) {
            $error_message = app_lang('minimum_payment_validation_message') . " " . to_currency($this->stripe_config->minimum_payment_amount, $currency . " ");
            $session = \Config\Services::session();
            $session->setFlashdata("error_message", $error_message);
            if ($verification_code) {
                $redirect_to = "pay_invoice/index/$verification_code";
            } else {
                $redirect_to = "invoices/preview/$invoice_id";
            }
            app_redirect($redirect_to);
        }

        //we'll verify the transaction with a random string code after completing the transaction
        $payment_verification_code = make_random_string();

        $stripe_ipn_data = array(
            "verification_code" => $verification_code,
            "invoice_id" => $invoice_id,
            "contact_user_id" => $contact_user_id,
            "client_id" => $client_id,
            "payment_method_id" => $payment_method_id,
            "payment_verification_code" => $payment_verification_code
        );

        \Stripe\Stripe::setApiKey($this->stripe_config->secret_key);
        $session = \Stripe\Checkout\Session::create(array(
                    'payment_method_types' => array('card'),
                    'line_items' => array(
                        array(
                            'name' => 'INVOICE #' . $invoice_id,
                            'description' => $description,
                            'amount' => $payment_amount * 100, //stripe will devide it with 100
                            'currency' => $currency,
                            'quantity' => 1,
                            'images' => array(
                                get_file_uri("assets/images/stripe-payment-logo.png")
                            )
                        )
                    ),
                    'payment_intent_data' => array(
                        "description" => get_invoice_id($invoice_id) . ", " . app_lang('amount') . ": " . to_currency($payment_amount, $currency . " "),
                        "metadata" => $stripe_ipn_data,
                        'setup_future_usage' => 'off_session', //save this paymentIntent's payment method for future use
                    ),
                    'success_url' => get_uri("stripe_redirect/index/$payment_verification_code"),
                    'cancel_url' => get_uri("stripe_redirect/index/$payment_verification_code"),
        ));

        if ($session->id) {
            //so, the session creation is success
            //save ipn data to db
            $stripe_ipn_data["payment_intent"] = $session->payment_intent;
            $Stripe_ipn_model = model("App\Models\Stripe_ipn_model");
            $Stripe_ipn_model->ci_save($stripe_ipn_data);

            return $session;
        }
    }

    public function get_publishable_key() {
        return $this->stripe_config->publishable_key;
    }

    public function is_valid_ipn($payment_intent) {
        \Stripe\Stripe::setApiKey($this->stripe_config->secret_key);
        $payment = \Stripe\PaymentIntent::retrieve($payment_intent);
        if ($payment && $payment->status == "succeeded") {
            //so the payment is successful
            return $payment;
        }
    }

}
