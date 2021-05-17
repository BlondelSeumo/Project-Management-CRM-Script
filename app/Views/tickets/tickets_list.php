<div id="page-content" class="page-wrapper clearfix">

    <ul class="nav nav-tabs bg-white title" role="tablist">
        <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo app_lang('tickets'); ?></h4></li>

        <?php echo view("tickets/index", array("active_tab" => "tickets_list")); ?>

        <div class="tab-title clearfix no-border">
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("labels/modal_form"), "<i data-feather='tag' class='icon-16'></i> " . app_lang('manage_labels'), array("class" => "btn btn-default", "title" => app_lang('manage_labels'), "data-post-type" => "ticket")); ?>
                <?php echo modal_anchor(get_uri("tickets/settings_modal_form"), "<i data-feather='settings' class='icon-16'></i> " . app_lang('settings'), array("class" => "btn btn-default", "title" => app_lang('settings'))); ?>
                <?php echo modal_anchor(get_uri("tickets/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_ticket'), array("class" => "btn btn-default", "title" => app_lang('add_ticket'))); ?>
            </div>
        </div>

    </ul>

    <div class="card no-border-top-radius">
        <div class="table-responsive pb50">
            <table id="ticket-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>

</div>


<script type="text/javascript">
    $(document).ready(function () {

        var optionsVisibility = false;
        if ("<?php
                if (isset($show_options_column) && $show_options_column) {
                    echo '1';
                }
                ?>" == "1") {
            optionsVisibility = true;
        }

        var projectVisibility = false;
        if ("<?php echo $show_project_reference; ?>" == "1") {
            projectVisibility = true;
        }

        var selectOpenStatus = true, selectClosedStatus = false;
<?php if (isset($status) && $status == "closed") { ?>
            selectOpenStatus = false;
            selectClosedStatus = true;
<?php } ?>

        $("#ticket-table").appTable({
            source: '<?php echo_uri("tickets/list_data") ?>',
            order: [[6, "desc"]],
            radioButtons: [{text: '<?php echo app_lang("open") ?>', name: "status", value: "open", isChecked: selectOpenStatus}, {text: '<?php echo app_lang("closed") ?>', name: "status", value: "closed", isChecked: selectClosedStatus}],
            filterDropdown: [{name: "ticket_type_id", class: "w200", options: <?php echo $ticket_types_dropdown; ?>}, {name: "ticket_label", class: "w200", options: <?php echo $ticket_labels_dropdown; ?>}, {name: "assigned_to", class: "w200", options: <?php echo $assigned_to_dropdown; ?>}],
            singleDatepicker: [{name: "created_at", defaultText: "<?php echo app_lang('created') ?>",
                    options: [
                        {value: moment().subtract(2, 'days').format("YYYY-MM-DD"), text: "<?php echo sprintf(app_lang('in_last_number_of_days'), 2); ?>"},
                        {value: moment().subtract(7, 'days').format("YYYY-MM-DD"), text: "<?php echo sprintf(app_lang('in_last_number_of_days'), 7); ?>"},
                        {value: moment().subtract(15, 'days').format("YYYY-MM-DD"), text: "<?php echo sprintf(app_lang('in_last_number_of_days'), 15); ?>"},
                        {value: moment().subtract(1, 'months').format("YYYY-MM-DD"), text: "<?php echo sprintf(app_lang('in_last_number_of_month'), 1); ?>"},
                        {value: moment().subtract(3, 'months').format("YYYY-MM-DD"), text: "<?php echo sprintf(app_lang('in_last_number_of_months'), 3); ?>"}
                    ]}],
            columns: [
                {title: '<?php echo app_lang("ticket_id") ?>', "class": "w10p"},
                {title: '<?php echo app_lang("title") ?>'},
                {title: '<?php echo app_lang("client") ?>', "class": "w15p"},
                {title: '<?php echo app_lang("project") ?>', "class": "w15p", visible: projectVisibility},
                {title: '<?php echo app_lang("ticket_type") ?>', "class": "w10p"},
                {title: '<?php echo app_lang("assigned_to") ?>', "class": "w10p"},
                {visible: false, searchable: false},
                {title: '<?php echo app_lang("last_activity") ?>', "iDataSort": 6, "class": "w10p"},
                {title: '<?php echo app_lang("status") ?>', "class": "w5p"}
<?php echo $custom_field_headers; ?>,
                {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center dropdown-option w50", visible: optionsVisibility}
            ],
            printColumns: combineCustomFieldsColumns([0, 2, 1, 3, 4, 6, 7], '<?php echo $custom_field_headers; ?>'),
            xlsColumns: combineCustomFieldsColumns([0, 2, 1, 3, 4, 6, 7], '<?php echo $custom_field_headers; ?>')
        });

    });
</script>