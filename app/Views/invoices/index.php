<div id="page-content" class="page-wrapper clearfix">
    <div class="card clearfix">
        <ul id="invoices-tabs" data-bs-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo app_lang("invoices"); ?></h4></li>
            <li><a id="monthly-expenses-button"  role="presentation"  href="javascript:;" data-bs-target="#monthly-invoices"><?php echo app_lang("monthly"); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("invoices/yearly/"); ?>" data-bs-target="#yearly-invoices"><?php echo app_lang('yearly'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("invoices/custom/"); ?>" data-bs-target="#custom-invoices"><?php echo app_lang('custom'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("invoices/recurring/"); ?>" data-bs-target="#recurring-invoices"><?php echo app_lang('recurring'); ?></a></li>
            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    <?php if ($can_edit_invoices) { ?>
                        <?php echo modal_anchor(get_uri("labels/modal_form"), "<i data-feather='tag' class='icon-16'></i> " . app_lang('manage_labels'), array("class" => "btn btn-default mb0", "title" => app_lang('manage_labels'), "data-post-type" => "invoice")); ?>
                        <?php echo modal_anchor(get_uri("invoice_payments/payment_modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_payment'), array("class" => "btn btn-default mb0", "title" => app_lang('add_payment'))); ?>
                        <?php echo modal_anchor(get_uri("invoices/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_invoice'), array("class" => "btn btn-default mb0", "title" => app_lang('add_invoice'))); ?>
                    <?php } ?>
                </div>
            </div>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="monthly-invoices">
                <div class="table-responsive">
                    <table id="monthly-invoice-table" class="display" cellspacing="0" width="100%">   
                    </table>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="yearly-invoices"></div>
            <div role="tabpanel" class="tab-pane fade" id="custom-invoices"></div>
            <div role="tabpanel" class="tab-pane fade" id="recurring-invoices"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    loadInvoicesTable = function (selector, dateRange) {
    var customDatePicker = "";
    if (dateRange === "custom") {
    customDatePicker = [{startDate: {name: "start_date", value: moment().format("YYYY-MM-DD")}, endDate: {name: "end_date", value: moment().format("YYYY-MM-DD")}, showClearButton: true}];
    dateRange = "";
    }

    var optionVisibility = false;
    if ("<?php echo $can_edit_invoices ?>") {
    optionVisibility = true;
    }

    $(selector).appTable({
    source: '<?php echo_uri("invoices/list_data") ?>',
            dateRangeType: dateRange,
            order: [[0, "desc"]],
            filterDropdown: [
            {name: "status", class: "w150", options: <?php echo view("invoices/invoice_statuses_dropdown"); ?>},
<?php if ($currencies_dropdown) { ?>
                {name: "currency", class: "w150", options: <?php echo $currencies_dropdown; ?>}
<?php } ?>
            ],
            rangeDatepicker: customDatePicker,
            columns: [
            {title: "<?php echo app_lang("invoice_id") ?>", "class": "w10p"},
            {title: "<?php echo app_lang("client") ?>", "class": ""},
            {title: "<?php echo app_lang("project") ?>", "class": "w15p"},
            {visible: false, searchable: false},
            {title: "<?php echo app_lang("bill_date") ?>", "class": "w10p", "iDataSort": 3},
            {visible: false, searchable: false},
            {title: "<?php echo app_lang("due_date") ?>", "class": "w10p", "iDataSort": 5},
            {title: "<?php echo app_lang("invoice_value") ?>", "class": "w10p text-right"},
            {title: "<?php echo app_lang("payment_received") ?>", "class": "w10p text-right"},
            {title: "<?php echo app_lang("status") ?>", "class": "w10p text-center"}
<?php echo $custom_field_headers; ?>,
            {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center dropdown-option w100", visible: optionVisibility}
            ],
            printColumns: combineCustomFieldsColumns([0, 1, 2, 3, 5, 7, 8, 9], '<?php echo $custom_field_headers; ?>'),
            xlsColumns: combineCustomFieldsColumns([0, 1, 2, 3, 5, 7, 8, 9], '<?php echo $custom_field_headers; ?>'),
            summation: [{column: 7, dataType: 'number'}, {column: 8, dataType: 'number'}]
    });
    };
    $(document).ready(function () {
    loadInvoicesTable("#monthly-invoice-table", "monthly");
    });
</script>