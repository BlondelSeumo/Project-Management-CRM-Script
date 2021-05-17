<?php echo form_open($paytm_url, array("id" => "paytm-online-payment-form", "class" => "float-start", "role" => "form")); ?>

<input type="hidden" name="MID" value="<?php echo get_array_value($payment_method, "merchant_id"); ?>" />
<input type="hidden" name="ORDER_ID" value="<?php echo "INVOICE-" . $invoice_id . "-" . rand(10000, 99999999); ?>" />
<input type="hidden" name="CUST_ID" value="<?php echo "CLIENT-" . $invoice_info->client_id . "-" . rand(10000, 99999999); ?>" />
<input type="hidden" name="INDUSTRY_TYPE_ID" value="<?php echo get_array_value($payment_method, "industry_type"); ?>" />
<input type="hidden" name="CHANNEL_ID" value="WEB" />
<input type="hidden" name="TXN_AMOUNT" value="<?php echo unformat_currency($balance_due); ?>" id="paytm-payment-amount-field" />
<input type="hidden" name="WEBSITE" value="<?php echo get_array_value($payment_method, "merchant_website"); ?>"/>
<input type="hidden" name="CALLBACK_URL" value="" id="paytm-callback-url"/>
<input type="hidden" name="CHECKSUMHASH" value="" id="paytm-checksum-hash" />

<button type="button" id="paytm-online-payment-button" class="btn btn-primary spinning-btn"><?php echo get_array_value($payment_method, "pay_button_text"); ?></button>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        var minimumPaymentAmount = "<?php echo get_array_value($payment_method, 'minimum_payment_amount'); ?>" * 1;
        if (!minimumPaymentAmount || isNaN(minimumPaymentAmount)) {
            minimumPaymentAmount = 1;
        }

        $("#payment-amount").change(function () {
            //change paytm payment amount
            var value = unformatCurrency($(this).val());

            $("#paytm-payment-amount-field").val(value);

            //check minimum payment amount and show/hide payment button
            if (value < minimumPaymentAmount) {
                $("#paytm-online-payment-button").hide();
            } else {
                $("#paytm-online-payment-button").show();
            }
        });

        $("#paytm-online-payment-button").click(function () {

            //show an error message if user attempt to pay more than the invoice due and exit
<?php if (get_setting("allow_partial_invoice_payment_from_clients")) { ?>
                if (unformatCurrency($("#payment-amount").val()) > "<?php echo $balance_due; ?>") {
                    appAlert.error("<?php echo app_lang("invoice_over_payment_error_message"); ?>");
                    return false;
                }
<?php } ?>

            //get the checksum hash 
            var data = {};
            $("#paytm-online-payment-form input").each(function () {
                if ($(this).attr("name") !== "CHECKSUMHASH") { //we shouldn't include this to get chacksum hash
                    data[$(this).attr("name")] = $(this).val();
                }
            });

<?php $checksum_hash_url = isset($contact_user_id) ? 'pay_invoice/get_paytm_checksum_hash' : 'invoice_payments/get_paytm_checksum_hash'; ?>

            var verificationDataArray = {
                invoice_id: "<?php echo $invoice_info->id; ?>",
                contact_user_id: "<?php echo isset($contact_user_id) ? $contact_user_id : $login_user->id; ?>",
                client_id: "<?php echo $invoice_info->client_id; ?>",
                payment_method_id: "<?php echo get_array_value($payment_method, "id"); ?>",
                verification_code: "<?php echo isset($verification_code) ? $verification_code : ""; ?>"
            };

            $.ajax({
                url: "<?php echo get_uri($checksum_hash_url); ?>",
                type: 'POST',
                dataType: 'json',
                data: {input_data: data, verification_data: JSON.stringify(verificationDataArray)},
                success: function (result) {
                    if (result.success) {
                        $('#paytm-checksum-hash').val(result.checksum_hash);
                        $('#paytm-callback-url').val("<?php echo get_uri("paytm_redirect/index"); ?>/" + result.payment_verification_code);

                        setTimeout(function () {
                            $("#paytm-online-payment-form").trigger("submit");
                        }, 200);
                    } else {
                        appAlert.error(result.message);
                    }
                }
            });

            $(this).addClass("spinning");
        });



    });
</script>