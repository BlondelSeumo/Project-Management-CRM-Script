<div id="page-content" class="page-wrapper clearfix">
    <?php
    load_css(array(
        "assets/css/invoice.css",
    ));
    ?>

    <div class="invoice-preview">
        <?php if ($login_user->user_type === "client" && $invoice_total_summary->balance_due >= 1 && count($payment_methods) && !$client_info->disable_online_payment) { ?>
            <div class="card d-block p15 no-border clearfix">
                <div class="inline-block strong float-start pt5 pr15">
                    <?php echo app_lang("pay_invoice"); ?>:
                </div>
                <div class="mr15 strong float-start general-form" style="width: 145px;" >
                    <?php if (get_setting("allow_partial_invoice_payment_from_clients")) { ?>
                        <span class="invoice-payment-amount-section" style="background-color: #f6f8f9; display: inline-block; padding: 8px 2px 7px 10px;"><?php echo $invoice_total_summary->currency; ?></span><input type="text" id="payment-amount" value="<?php echo to_decimal_format($invoice_total_summary->balance_due); ?>" class="form-control inline-block fw-bold" style="padding-left: 3px; width: 100px" />
                    <?php } else { ?>
                        <span class="pt5 inline-block">
                            <?php echo to_currency($invoice_total_summary->balance_due, $invoice_total_summary->currency . " "); ?>
                        </span>
                    <?php } ?>
                </div>

                <?php
                foreach ($payment_methods as $payment_method) {

                    $method_type = get_array_value($payment_method, "type");

                    $pass_variables = array(
                        "payment_method" => $payment_method,
                        "balance_due" => $invoice_total_summary->balance_due,
                        "currency" => $invoice_total_summary->currency,
                        "invoice_info" => $invoice_info,
                        "invoice_id" => $invoice_id,
                        "paypal_url" => $paypal_url);

                    if ($invoice_total_summary->balance_due >= get_array_value($payment_method, "minimum_payment_amount")) {
                        if ($method_type == "stripe") {
                            echo view("invoices/_stripe_payment_form", $pass_variables);
                        } else if ($method_type == "paypal_payments_standard") {
                            echo view("invoices/_paypal_payments_standard_form", $pass_variables);
                        } else if ($method_type == "paytm") {
                            echo view("invoices/_paytm_payment_form", $pass_variables);
                        }
                    }
                }
                ?>
                <div class="float-end">
                    <?php
                    echo "<div class='text-center'>" . anchor("invoices/download_pdf/" . $invoice_info->id, app_lang("download_pdf"), array("class" => "btn btn-default round")) . "</div>"
                    ?>
                </div>

            </div>
            <?php
        } else if ($login_user->user_type === "client") {
            echo "<div class='text-center'>" . anchor("invoices/download_pdf/" . $invoice_info->id, app_lang("download_pdf"), array("class" => "btn btn-default round")) . "</div>";
        }


        if ($show_close_preview) {
            echo "<div class='text-center'>" . anchor("invoices/view/" . $invoice_info->id, app_lang("close_preview"), array("class" => "btn btn-default round")) . "</div>";
        }
        ?>

        <div class="invoice-preview-container bg-white mt15">
            <div class="row">
                <div class="col-md-12 position-relative">
                    <div class="ribbon"><?php echo $invoice_status_label; ?></div>
                </div>
            </div>

            <?php
            echo $invoice_preview;
            ?>
        </div>

    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#payment-amount").change(function () {
            var value = $(this).val();
            $(".payment-amount-field").each(function () {
                $(this).val(value);
            });
        });
    });



</script>
