<div class="card">
    <div class="tab-title clearfix">
        <h4><?php echo app_lang('estimates'); ?></h4>
        <div class="title-button-group">
            <?php echo modal_anchor(get_uri("estimates/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_estimate'), array("class" => "btn btn-default", "data-post-client_id" => $client_id, "title" => app_lang('add_estimate'))); ?>
        </div>
    </div>
    <div class="table-responsive">
        <table id="estimate-table" class="display" width="100%">
            <tfoot>
                <tr>
                    <th colspan="4" class="text-right"><?php echo app_lang("total") ?>:</th>
                    <th class="text-right" data-current-page="4"></th>
                    <th> </th>
                </tr>
                <tr data-section="all_pages">
                    <th colspan="4" class="text-right"><?php echo app_lang("total_of_all_pages") ?>:</th>
                    <th class="text-right" data-all-page="4"></th>
                    <th> </th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var currencySymbol = "<?php echo $lead_info->currency_symbol; ?>";
        $("#estimate-table").appTable({
            source: '<?php echo_uri("estimates/estimate_list_data_of_client/" . $client_id) ?>',
            order: [[0, "desc"]],
            columns: [
                {title: "<?php echo app_lang("estimate") ?>", "class": "w25p"},
                {visible: false, searchable: false},
                {visible: false, searchable: false},
                {title: "<?php echo app_lang("estimate_date") ?>", "iDataSort": 2, "class": "w25p"},
                {title: "<?php echo app_lang("amount") ?>", "class": "text-right w25p"},
                {title: "<?php echo app_lang("status") ?>", "class": "text-center w25p"}
<?php echo $custom_field_headers; ?>,
                {visible: false}
            ],
            summation: [{column: 4, dataType: 'currency', currencySymbol: currencySymbol}]
        });
    });
</script>