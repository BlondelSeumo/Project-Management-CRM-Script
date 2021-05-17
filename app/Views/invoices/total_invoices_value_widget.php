<?php
$card = "";
$icon = "";
$value = "";
$lang = "";
$link = "";

if ($type == "invoices") {
    $lang = app_lang("invoice_value");
    $card = "bg-primary";
    $icon = "file-text";
    $value = to_currency($invoices_info->invoices_total);
    $link = get_uri('invoices/index');
} else if ($type == "payments") {
    $lang = app_lang("payments");
    $card = "bg-success";
    $icon = "check-square";
    $value = to_currency($invoices_info->payments_total);
    $link = get_uri('invoice_payments/index');
} else if ($type == "due") {
    $lang = app_lang("due");
    $card = "bg-coral";
    $icon = "dollar-sign";
    $value = to_currency(ignor_minor_value($invoices_info->due));
    $link = get_uri('invoices/index');
} else if ($type == "draft") {
    $lang = app_lang("draft_invoices_total");
    $card = "bg-orange";
    $icon = "file-text";
    $value = to_currency($invoices_info->draft_total);
    $link = get_uri('invoices/index');
}
?>

<a href="<?php echo $link; ?>" class="white-link">
    <div class="card  dashboard-icon-widget">
        <div class="card-body ">
            <div class="widget-icon <?php echo $card ?>">
                <i data-feather="<?php echo $icon; ?>" class="icon"></i>
            </div>
            <div class="widget-details">
                <h1><?php echo $value; ?></h1>
                <span><?php echo $lang; ?></span>
            </div>
        </div>
    </div>
</a>