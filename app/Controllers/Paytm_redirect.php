<?php

namespace App\Controllers;

use App\Libraries\Paytm;

//don't extend this controller from Pre_loader 
//because this will be called by Paytm
//and login check is not required since we'll validate the data

class Paytm_redirect extends App_Controller {

    function __construct() {
        parent::__construct();
    }

    function index($payment_verification_code = "") {
        if (!($payment_verification_code && isset($_POST["CHECKSUMHASH"]))) {
            show_404();
        }

        $paytm = new Paytm();

        //get verification data
        $options = array("code" => $payment_verification_code, "type" => "invoice_payment");
        $verification_info = $this->Verification_model->get_details($options)->getRow();
        if (!($verification_info && $verification_info->id)) {
            show_404();
        }

        $payment_data = unserialize($verification_info->params);
        $verification_code = get_array_value($payment_data, "verification_code");
        $invoice_id = get_array_value($payment_data, "invoice_id");

        $data_array = $_POST;

        //check if it's a success transaction
        if (get_array_value($data_array, "STATUS") !== "TXN_SUCCESS") {
            //failed transaction, redirect with error message
            $this->session->setFlashdata("error_message", get_array_value($data_array, "RESPMSG"));
            $this->redirect_to_invoice($invoice_id, $verification_code);
        }

        //validate the checksum hash
        $is_valid_checksum_hash = $paytm->is_valid_checksum_hash($data_array);
        if (!$is_valid_checksum_hash) {
            show_404();
        }

        //so, checksum hash is valid. now update the invoice payment in db
        //set login user id = contact id for future processing
        $this->login_user = new \stdClass();
        $this->login_user->id = get_array_value($payment_data, "contact_user_id");
        $this->login_user->user_type = "client";

        //payment complete, insert payment record
        $invoice_payment_data = array(
            "invoice_id" => $invoice_id,
            "payment_date" => get_current_utc_time(),
            "payment_method_id" => get_array_value($payment_data, "payment_method_id"),
            "note" => "",
            "amount" => get_array_value($data_array, "TXNAMOUNT"),
            "transaction_id" => get_array_value($data_array, "TXNID"),
            "created_at" => get_current_utc_time(),
            "created_by" => $this->login_user->id,
        );

        //check if already a payment done with this transaction
        $existing = $this->Invoice_payments_model->get_one_where(array("transaction_id" => get_array_value($data_array, "TXNID")));
        if ($existing->id) {
            show_404();
        }

        $invoice_payment_id = $this->Invoice_payments_model->ci_save($invoice_payment_data);
        if (!$invoice_payment_id) {
            show_404();
        }

        //as receiving payment for the invoice, we'll remove the 'draft' status from the invoice 
        $this->Invoices_model->update_invoice_status($invoice_id);

        log_notification("invoice_payment_confirmation", array("invoice_payment_id" => $invoice_payment_id, "invoice_id" => $invoice_id), "0");
        log_notification("invoice_online_payment_received", array("invoice_payment_id" => $invoice_payment_id, "invoice_id" => $invoice_id), $this->login_user->id);

        //delete payment verification data
        $this->Verification_model->delete_permanently($verification_info->id);

        $this->session->setFlashdata("success_message", app_lang("payment_success_message"));
        $this->redirect_to_invoice($invoice_id, $verification_code);
    }

    private function redirect_to_invoice($invoice_id = 0, $verification_code = "") {
        $redirect_to = "invoices/preview/$invoice_id";
        if ($verification_code) {
            $redirect_to = "pay_invoice/index/$verification_code";
        }

        app_redirect($redirect_to);
    }

}

/* End of file Paytm_redirect.php */
/* Location: ./app/controllers/Paytm_redirect.php */