<?php if (isset($page_type) && $page_type === "full") { ?>
    <div id="page-content" class="page-wrapper clearfix">
    <?php } ?>

    <div class="card rounded-0">
        <?php if (isset($page_type) && $page_type === "full") { ?>
            <div class="page-title clearfix">
                <h1><?php echo app_lang('payments'); ?></h1>
            </div>
        <?php } else { ?>
            <div class="tab-title clearfix">
                <h4><?php echo app_lang('payments'); ?></h4>
            </div>
        <?php } ?>

        <div class="table-responsive">
            <table id="invoice-payment-table" class="display" width="100%">
            </table>
        </div>
    </div>
    <?php if (isset($page_type) && $page_type === "full") { ?>
    </div>
<?php } ?>

<script type="text/javascript">
    $(document).ready(function () {
        var currencySymbol = "<?php echo $client_info->currency_symbol; ?>";
        $("#invoice-payment-table").appTable({
            source: '<?php echo_uri("invoice_payments/payment_list_data_of_client/" . $client_id) ?>',
            order: [[1, "desc"]],
            columns: [
                {title: '<?php echo app_lang("invoice_id") ?> ', "class": "w10p"},
                {visible: false, searchable: false},
                {title: '<?php echo app_lang("payment_date") ?> ', "class": "w15p", "iDataSort": 1},
                {title: '<?php echo app_lang("payment_method") ?>', "class": "w15p"},
                {title: '<?php echo app_lang("note") ?>'},
                {title: '<?php echo app_lang("amount") ?>', "class": "text-right w15p"}
            ],
            printColumns: [0, 1, 2, 3, 4],
            xlsColumns: [0, 1, 2, 3, 4],
            summation: [{column: 5, dataType: 'currency', currencySymbol: currencySymbol}]
        });

    });
</script>