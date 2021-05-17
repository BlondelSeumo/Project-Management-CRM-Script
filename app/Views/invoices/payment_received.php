<div id="page-content" class="page-wrapper clearfix">
    <div class="card clearfix">
        <ul id="payment-received-tabs" data-bs-toggle="ajax-tab" class="nav nav-tabs bg-white inner" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo app_lang("payment_received"); ?></h4></li>
            <li><a id="monthly-payment-button"  role="presentation"  href="javascript:;" data-bs-target="#monthly-payments"><?php echo app_lang("monthly"); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("invoice_payments/yearly/"); ?>" data-bs-target="#yearly-payments"><?php echo app_lang('yearly'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("invoice_payments/custom/"); ?>" data-bs-target="#custom-payments"><?php echo app_lang('custom'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("invoice_payments/yearly_chart/"); ?>" data-bs-target="#yearly-chart"><?php echo app_lang('chart'); ?></a></li>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="monthly-payments">
                <div class="table-responsive">
                    <table id="monthly-invoice-payment-table" class="display" width="100%">
                    </table>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="yearly-payments"></div>
            <div role="tabpanel" class="tab-pane fade" id="custom-payments"></div>
            <div role="tabpanel" class="tab-pane fade" id="yearly-chart"></div>
        </div>
    </div>
</div>


<div class="card clearfix">
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade" id="team_member-monthly-leaves">
            <div class="table-responsive">
                <table id="monthly-leaves-table" class="display" cellspacing="0" width="100%">            
                </table>
            </div>
            <script type="text/javascript">
                loadPaymentsTable = function (selector, dateRange) {
                var customDatePicker = "";
                if (dateRange === "custom") {
                customDatePicker = [{startDate: {name: "start_date", value: moment().format("YYYY-MM-DD")}, endDate: {name: "end_date", value: moment().format("YYYY-MM-DD")}, showClearButton: true}];
                dateRange = "";
                }

                $(selector).appTable({
                source: '<?php echo_uri("invoice_payments/payment_list_data/") ?>',
                        order: [[0, "asc"]],
                        dateRangeType: dateRange,
                        filterDropdown: [
                        {name: "payment_method_id", class: "w200", options: <?php echo $payment_method_dropdown; ?>},
<?php if ($currencies_dropdown) { ?>
                            {name: "currency", class: "w150", options: <?php echo $currencies_dropdown; ?>},
<?php } ?>
<?php if ($projects_dropdown) { ?>
                            {name: "project_id", class: "w200", options: <?php echo $projects_dropdown; ?>}
<?php } ?>
                        ],
                        rangeDatepicker: customDatePicker,
                        columns: [
                        {title: '<?php echo app_lang("invoice_id") ?> ', "class": "w10p"},
                        {visible: false, searchable: false},
                        {title: '<?php echo app_lang("payment_date") ?> ', "class": "w15p", "iDataSort": 1},
                        {title: '<?php echo app_lang("payment_method") ?>', "class": "w15p"},
                        {title: '<?php echo app_lang("note") ?>'},
                        {title: '<?php echo app_lang("amount") ?>', "class": "text-right w15p"}
                        ],
                        summation: [{column: 5, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol}],
                        printColumns: [0, 1, 2, 3, 4],
                        xlsColumns: [0, 1, 2, 3, 4]
                });
                };
                $(document).ready(function () {
                loadPaymentsTable("#monthly-invoice-payment-table", "monthly");
                });
            </script>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="team_member-yearly-leaves"></div>
    </div>
</div>