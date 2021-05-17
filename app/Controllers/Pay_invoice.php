<?php

namespace App\Controllers;

use App\Libraries\Paypal;
use App\Libraries\Paytm;
use App\Libraries\Stripe;

class Pay_invoice extends App_Controller {

    function __construct() {
        parent::__construct();
    }

    function index($verification_code = "") {
        if (!get_setting("client_can_pay_invoice_without_login")) {
            app_redirect("forbidden");
        }

        if ($verification_code) {
            $options = array("code" => $verification_code, "type" => "invoice_payment");
            $verification_info = $this->Verification_model->get_details($options)->getRow();

            if ($verification_info && $verification_info->id) {
                $invoice_data = unserialize($verification_info->params);

                $invoice_id = get_array_value($invoice_data, "invoice_id");
                $client_id = get_array_value($invoice_data, "client_id");
                $contact_id = get_array_value($invoice_data, "contact_id");

                $this->_log("invoice_id:$invoice_id, client_id:$client_id, contact_id:$contact_id");

                if ($invoice_id && is_numeric($invoice_id) && $client_id && is_numeric($client_id) && $contact_id && is_numeric($contact_id)) {
                    $view_data = get_invoice_making_data($invoice_id);
                    $view_data['payment_methods'] = $this->Payment_methods_model->get_available_online_payment_methods();

                    //check access of this invoice
                    $this->_check_access_of_invoice($view_data);

                    $view_data['invoice_preview'] = prepare_invoice_pdf($view_data, "html");

                    $view_data['invoice_id'] = $invoice_id;

                    $paypal = new Paypal();
                    $view_data['paypal_url'] = $paypal->get_paypal_url();

                    $paytm = new Paytm();
                    $view_data['paytm_url'] = $paytm->get_paytm_url();

                    $view_data['contact_id'] = $contact_id;
                    $view_data['verification_code'] = $verification_code;

                    return $this->template->view("invoices/public_invoice_preview", $view_data);
                } else {
                    show_404();
                }
            } else {
                show_404();
            }
        }
    }

    private function _check_access_of_invoice($view_data) {
        if (count($view_data) && count(get_array_value($view_data, 'payment_methods')) && !get_array_value($view_data, "client_info")->disable_online_payment) {
            return true;
        } else {
            app_redirect("forbidden");
        }
    }

    function get_stripe_payment_intent_session() {
        if (!get_setting("client_can_pay_invoice_without_login")) {
            app_redirect("forbidden");
        }

        $stripe = new Stripe();

        try {
            $session = $stripe->get_stripe_payment_intent_session($this->request->getPost("input_data"));
            if ($session->id) {
                echo json_encode(array("success" => true, "session_id" => $session->id, "publishable_key" => $stripe->get_publishable_key()));
            } else {
                echo json_encode(array('success' => false, 'message' => app_lang('error_occurred')));
            }
        } catch (Exception $ex) {
            echo json_encode(array("success" => false, "message" => $ex->getMessage()));
        }
    }

    private function _log($text = "") {
        if ($text && get_setting("enable_public_pay_invoice_logging")) {
            error_log(date('[Y-m-d H:i e] ') . $text . PHP_EOL, 3, "public_pay_invoice_logs.txt");
        }
    }

    function get_paytm_checksum_hash() {
        $paytm = new Paytm();
        $payment_data = $paytm->get_paytm_checksum_hash($this->request->getPost("input_data"), $this->request->getPost("verification_data"));

        if ($payment_data) {
            echo json_encode(array("success" => true, "checksum_hash" => get_array_value($payment_data, "checksum_hash"), "payment_verification_code" => get_array_value($payment_data, "payment_verification_code")));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang("paytm_checksum_hash_error_message")));
        }
    }

}

/* End of file Pay_invoice.php */
/* Location: ./app/controllers/Pay_invoice.php */