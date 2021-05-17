<?php
$form_action = isset($contact_user_id) ? get_uri("pay_invoice/get_stripe_payment_intent_session") : get_uri("invoice_payments/get_stripe_payment_intent_session");
echo form_open("", array("id" => "stripe-checkout-form", "class" => "float-start", "role" => "form"));
?>
<input type="hidden" name="invoice_id" value="<?php echo $invoice_id; ?>" />
<input type="hidden" name="payment_amount" value="<?php echo to_decimal_format($balance_due); ?>"  id="stripe-payment-amount-field" />
<input type="hidden" name="verification_code" value="<?php echo isset($verification_code) ? $verification_code : ""; ?>"  id="verification_code" />
<input type="hidden" name="contact_user_id" value="<?php echo isset($contact_user_id) ? $contact_user_id : ""; ?>"  id="contact_user_id" />

<input type="hidden" name="currency" value="<?php echo $currency; ?>" />
<input type="hidden" name="balance_due" value="<?php echo $balance_due; ?>" />
<input type="hidden" name="client_id" value="<?php echo $invoice_info->client_id; ?>" />
<input type="hidden" name="payment_method_id" value="<?php echo get_array_value($payment_method, "id"); ?>" />
<input type="hidden" name="description" value="<?php echo app_lang("pay_invoice"); ?>: (<?php echo to_currency($balance_due, $currency . " "); ?>)" id="description" />

<button type="button" id="stripe-payment-button" class="btn btn-primary mr15 spinning-btn"><?php echo get_array_value($payment_method, "pay_button_text"); ?></button>
<?php echo form_close(); ?>

<script src="https://js.stripe.com/v3/"></script>

<script type="text/javascript">
    $(document).ready(function () {
        var currency = "<?php echo $currency . ' '; ?>",
                payInvoiceText = "<?php echo app_lang("pay_invoice"); ?>";
        var $button = $("#stripe-payment-button");

        $button.on('click', function (event) {

            //show an error message if user attempt to pay more than the invoice due and exit
<?php if (get_setting("allow_partial_invoice_payment_from_clients")) { ?>
                if (unformatCurrency($("#payment-amount").val()) > "<?php echo $balance_due; ?>") {
                    appAlert.error("<?php echo app_lang("invoice_over_payment_error_message"); ?>");
                    return false;
                }
<?php } ?>

            $button.addClass("spinning");

            //prepare the data
            var data = {};
            $("#stripe-checkout-form input").each(function () {
                data[$(this).attr("name")] = $(this).val();
            });

            //get the payment intent session id
            $.ajax({
                url: "<?php echo $form_action; ?>",
                type: 'POST',
                dataType: 'json',
                data: {input_data: data},
                success: function (result) {
                    if (result.success && result.session_id && result.publishable_key) {
                        var stripe = Stripe(result.publishable_key);

                        stripe.redirectToCheckout({
                            sessionId: result.session_id
                        }).then(function (result) {
                            appAlert.error(result.error.message);
                        });
                    } else {
                        appAlert.error(result.message);
                    }
                }
            });
        });

        var minimumPaymentAmount = "<?php echo get_array_value($payment_method, 'minimum_payment_amount'); ?>" * 1;
        if (!minimumPaymentAmount || isNaN(minimumPaymentAmount)) {
            minimumPaymentAmount = 1;
        }

        $("#payment-amount").change(function () {
            //changed the amount. update the description on stripe payment form
            var value = $(this).val();
            $("#description").val(payInvoiceText + " (" + toCurrency(unformatCurrency(value), currency) + ")");

            //change stripe payment amount field value as inputed/ don't use unformatCurrency we'll do it in controller
            $("#stripe-payment-amount-field").val(value);

            //check minimum payment amount and show/hide payment button
            if (value < minimumPaymentAmount) {
                $("#stripe-payment-button").hide();
            } else {
                $("#stripe-payment-button").show();
            }

        });

    });
</script>