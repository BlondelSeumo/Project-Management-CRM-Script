<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <ul id="project-all-timesheet-tabs" data-bs-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo app_lang("timesheets"); ?></h4></li>

            <li><a id="timesheet-details-button"  role="presentation" href="javascript:;" data-bs-target="#timesheet-details"><?php echo app_lang("details"); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("projects/all_timesheet_summary/"); ?>" data-bs-target="#timesheet-summary"><?php echo app_lang('summary'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("projects/timesheet_chart/"); ?>" data-bs-target="#timesheet-chart"><?php echo app_lang('chart'); ?></a></li>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="timesheet-details">
                <div class="table-responsive">
                    <table id="all-project-timesheet-table" class="display" width="100%">  
                    </table>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="timesheet-summary"></div>
            <div role="tabpanel" class="tab-pane fade" id="timesheet-chart"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var endTimeVisibility = true;
<?php if (get_setting("users_can_input_only_total_hours_instead_of_period")) { ?>
            endTimeVisibility = false;
<?php } ?>

        $("#all-project-timesheet-table").appTable({
            source: '<?php echo_uri("projects/timesheet_list_data/") ?>',
            filterDropdown: [
                {name: "user_id", class: "w200", options: <?php echo $members_dropdown; ?>},
                {name: "project_id", class: "w200", options: <?php echo $projects_dropdown; ?>, dependency: ["client_id"], dataSource: '<?php echo_uri("projects/get_projects_of_selected_client_for_filter") ?>', selfDependency: true}, //projects are dependent on client. but we have to show all projects, if there is no selected client
<?php if ($login_user->is_admin || get_array_value($login_user->permissions, "client")) { ?>
                    {name: "client_id", class: "w200", options: <?php echo $clients_dropdown; ?>, dependent: ["project_id"]}, //reset projects on changing of client
<?php } ?>
            ],

            rangeDatepicker: [{startDate: {name: "start_date", value: moment().format("YYYY-MM-DD")}, endDate: {name: "end_date", value: moment().format("YYYY-MM-DD")}, showClearButton: true}],
            columns: [
                {title: "<?php echo app_lang('member') ?>"},
                {title: "<?php echo app_lang('project') ?>"},
                {title: "<?php echo app_lang('client') ?>"},
                {title: "<?php echo app_lang('task') ?>"},
                {visible: false, searchable: false},
                {title: "<?php echo get_setting("users_can_input_only_total_hours_instead_of_period") ? app_lang("date") : app_lang('start_time') ?>", "iDataSort": 4},
                {visible: false, searchable: false},
                {title: "<?php echo app_lang('end_time') ?>", "iDataSort": 6, visible: endTimeVisibility},
                {title: "<?php echo app_lang('total') ?>", "class": "text-right"},
                {title: '<i data-feather="message-circle" class="icon-16"></i>', "class": "text-center w50"}
<?php echo $custom_field_headers; ?>,
                {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
            ],
            printColumns: combineCustomFieldsColumns([0, 1, 2, 3, 5, 7, 8], '<?php echo $custom_field_headers; ?>'),
            xlsColumns: combineCustomFieldsColumns([0, 1, 2, 3, 5, 7, 8], '<?php echo $custom_field_headers; ?>'),
            summation: [{column: 8, dataType: 'time'}]
        });
    });
</script>