<div class="clearfix">
    <?php if ($show_invoice_info) { ?>
        <?php if (!in_array("projects", $hidden_menu)) { ?>
            <div class="col-md-3 col-sm-6 widget-container">
                <div class="card dashboard-icon-widget">
                    <div class="card-body ">
                        <div class="widget-icon bg-info">
                            <i data-feather='grid' class='icon'></i>
                        </div>
                        <div class="widget-details">
                            <h1><?php echo to_decimal_format($client_info->total_projects); ?></h1>
                            <span><?php echo app_lang("projects"); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php if (!in_array("invoices", $hidden_menu)) { ?>
            <div class="col-md-3 col-sm-6  widget-container">
                <div class="card dashboard-icon-widget">
                    <div class="card-body">
                        <div class="widget-icon bg-primary">
                            <i data-feather='file-text' class='icon'></i>
                        </div>
                        <div class="widget-details">
                            <h1><?php echo to_currency($client_info->invoice_value, $client_info->currency_symbol); ?></h1>
                            <div><?php echo app_lang("invoice_value"); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php if (!in_array("payments", $hidden_menu) && !in_array("invoices", $hidden_menu)) { ?>
            <div class="col-md-3 col-sm-6  widget-container">
                <div class="card dashboard-icon-widget">
                    <div class="card-body">
                        <div class="widget-icon bg-success">
                            <i data-feather='check-circle' class='icon'></i>
                        </div>
                        <div class="widget-details">
                            <h1><?php echo to_currency($client_info->payment_received, $client_info->currency_symbol); ?></h1>
                            <span><?php echo app_lang("payments"); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6  widget-container">
                <div class="card dashboard-icon-widget">
                    <div class="card-body">
                        <div class="widget-icon bg-coral">
                            <i data-feather='dollar-sign' class='icon'></i>
                        </div>
                        <div class="widget-details">
                            <h1><?php echo to_currency($client_info->invoice_value - $client_info->payment_received, $client_info->currency_symbol); ?></h1>
                            <span><?php echo app_lang("due"); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php if ((in_array("projects", $hidden_menu)) && (in_array("invoices", $hidden_menu))) { ?>
            <div class="col-sm-12 col-md-12" style="margin-top: 10%">
                <div class="text-center box">
                    <div class="box-content" style="vertical-align: middle; height: 100%">
                        <span data-feather="meh" height="15rem" width="15rem" style="color:#CBCED0;"></span>
                    </div>
                </div>
            </div>
        <?php } ?>

    <?php } ?>
</div>