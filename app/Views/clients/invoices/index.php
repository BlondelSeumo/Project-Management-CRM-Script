<?php if (isset($page_type) && $page_type === "full") { ?>
    <div id="page-content" class="page-wrapper clearfix">
    <?php } ?>

    <div class="card rounded-0">
        <?php if (isset($page_type) && $page_type === "full") { ?>
            <div class="page-title clearfix">
                <h1><?php echo app_lang('invoices'); ?></h1>
            </div>
        <?php } else { ?>
            <div class="tab-title clearfix">
                <h4><?php echo app_lang('invoices'); ?></h4>
                <div class="title-button-group">
                    <?php
                    if ($can_edit_invoices) {
                        echo modal_anchor(get_uri("invoices/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_invoice'), array("class" => "btn btn-default mb0", "data-post-client_id" => $client_id, "title" => app_lang('add_invoice')));
                    }
                    ?>
                </div>
            </div>
        <?php } ?>

        <div class="table-responsive">
            <table id="invoice-table" class="display" width="100%">
            </table>
        </div>
    </div>
    <?php if (isset($page_type) && $page_type === "full") { ?>
    </div>
<?php } ?>
<script type="text/javascript">
    $(document).ready(function () {
        var currencySymbol = "<?php echo $client_info->currency_symbol; ?>";
        $("#invoice-table").appTable({
            source: '<?php echo_uri("invoices/invoice_list_data_of_client/" . $client_id) ?>',
            order: [[0, "desc"]],
            filterDropdown: [{name: "status", class: "w150", options: <?php echo view("invoices/invoice_statuses_dropdown"); ?>}],
            columns: [
                {title: '<?php echo app_lang("id") ?>', "class": "w10p"},
                {targets: [1], visible: false, searchable: false},
                {title: "<?php echo app_lang("project") ?>"},
                {visible: false, searchable: false},
                {title: "<?php echo app_lang("bill_date") ?>", "class": "w10p", "iDataSort": 3},
                {visible: false, searchable: false},
                {title: "<?php echo app_lang("due_date") ?>", "class": "w10p", "iDataSort": 5},
                {title: "<?php echo app_lang("invoice_value") ?>", "class": "w10p text-right"},
                {title: "<?php echo app_lang("payment_received") ?>", "class": "w10p text-right"},
                {title: '<?php echo app_lang("status") ?>', "class": "w10p text-center"}
<?php echo $custom_field_headers; ?>
            ],
            printColumns: combineCustomFieldsColumns([0, 2, 4, 6, 7, 8, 9], '<?php echo $custom_field_headers; ?>'),
            xlsColumns: combineCustomFieldsColumns([0, 2, 4, 6, 7, 8, 9], '<?php echo $custom_field_headers; ?>'),
            summation: [{column: 7, dataType: 'currency', currencySymbol: currencySymbol}, {column: 8, dataType: 'currency', currencySymbol: currencySymbol}]
        });
    });
</script>