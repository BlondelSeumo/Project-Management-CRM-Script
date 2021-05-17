<div id="page-content" class="page-wrapper clearfix">
    <div class="card clearfix">
        <ul id="estimate-tabs" data-bs-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo app_lang('estimates'); ?></h4></li>
            <li><a id="monthly-estimate-button"  role="presentation" href="javascript:;" data-bs-target="#monthly-estimates"><?php echo app_lang("monthly"); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("estimates/yearly/"); ?>" data-bs-target="#yearly-estimates"><?php echo app_lang('yearly'); ?></a></li>
            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    <?php echo modal_anchor(get_uri("estimates/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_estimate'), array("class" => "btn btn-default", "title" => app_lang('add_estimate'))); ?>
                </div>
            </div>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="monthly-estimates">
                <div class="table-responsive">
                    <table id="monthly-estimate-table" class="display" cellspacing="0" width="100%">   
                    </table>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="yearly-estimates"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    loadEstimatesTable = function (selector, dateRange) {
        $(selector).appTable({
            source: '<?php echo_uri("estimates/list_data") ?>',
            order: [[0, "desc"]],
            dateRangeType: dateRange,
            filterDropdown: [{name: "status", class: "w150", options: <?php echo view("estimates/estimate_statuses_dropdown"); ?>}],
            columns: [
                {title: "<?php echo app_lang("estimate") ?> ", "class": "w15p"},
                {title: "<?php echo app_lang("client") ?>"},
                {visible: false, searchable: false},
                {title: "<?php echo app_lang("estimate_date") ?>", "iDataSort": 2, "class": "w20p"},
                {title: "<?php echo app_lang("amount") ?>", "class": "text-right w20p"},
                {title: "<?php echo app_lang("status") ?>", "class": "text-center"}
<?php echo $custom_field_headers; ?>,
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w100"}
            ],
            printColumns: combineCustomFieldsColumns([0, 1, 3, 4, 5], '<?php echo $custom_field_headers; ?>'),
            xlsColumns: combineCustomFieldsColumns([0, 1, 3, 4, 5], '<?php echo $custom_field_headers; ?>'),
            summation: [{column: 4, dataType: 'currency', currencySymbol: AppHelper.settings.currencySymbol}]
        });
    };

    $(document).ready(function () {
        loadEstimatesTable("#monthly-estimate-table", "monthly");
    });

</script>