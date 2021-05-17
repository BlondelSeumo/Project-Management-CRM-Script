<div class="card">
    <div class="card-header title-tab">
        <h4 class="float-start"><?php echo app_lang('tasks'); ?></h4>
        <div class="title-button-group">
            <?php
            if ($login_user->user_type == "staff" && $can_edit_tasks) {
                echo modal_anchor(get_uri("labels/modal_form"), "<i data-feather='tag' class='icon-16'></i> " . app_lang('manage_labels'), array("class" => "btn btn-outline-light", "title" => app_lang('manage_labels'), "data-post-type" => "task"));
                echo modal_anchor("", "<i data-feather='edit' class='icon-16'></i> " . app_lang('batch_update'), array("class" => "btn btn-info text-white hide batch-update-btn", "title" => app_lang('batch_update'), "data-post-project_id" => $project_id));
                echo js_anchor("<i data-feather='check-square' class='icon-16'></i> " . app_lang("batch_update"), array("class" => "btn btn-outline-light batch-active-btn"));
                echo js_anchor("<i data-feather='x-square' class='icon-16'></i> " . app_lang("cancel_selection"), array("class" => "hide btn btn-outline-light batch-cancel-btn"));
            }
            if ($can_create_tasks) {
                echo modal_anchor(get_uri("projects/task_modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_multiple_tasks'), array("class" => "btn btn-outline-light", "title" => app_lang('add_multiple_tasks'), "data-post-project_id" => $project_id, "data-post-add_type" => "multiple"));
                echo modal_anchor(get_uri("projects/task_modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_task'), array("class" => "btn btn-outline-light", "title" => app_lang('add_task'), "data-post-project_id" => $project_id));
            }
            ?>
        </div>
    </div>
    <div class="table-responsive">
        <table id="task-table" class="display" width="100%">            
        </table>
    </div>    
</div>

<?php
//prepare status dropdown list
//select the non completed tasks for team members by default
//show all tasks for client by default.
$statuses = array();
foreach ($task_statuses as $status) {
    $is_selected = false;
    if ($login_user->user_type == "staff") {
        if ($status->key_name != "done") {
            $is_selected = true;
        }
    }

    $statuses[] = array("text" => ($status->key_name ? app_lang($status->key_name) : $status->title), "value" => $status->id, "isChecked" => $is_selected);
}
?>

<script type="text/javascript">
    $(document).ready(function () {

    var userType = "<?php echo $login_user->user_type; ?>";
    var optionVisibility = false;
    if ("<?php echo ($can_edit_tasks || $can_delete_tasks); ?>") {
    optionVisibility = true;
    }

    var showResponsiveOption = true,
            idColumnClass = "w10p",
            titleColumnClass = "",
            optionColumnClass = "w100";
    if (isMobile()) {
    showResponsiveOption = false;
    idColumnClass = "w20p";
    titleColumnClass = "w60p";
    optionColumnClass = "w20p";
    }

    if (userType === "client") {
    //don't show assignee and options to clients
    $("#task-table").appTable({
    source: '<?php echo_uri("projects/tasks_list_data/" . $project_id) ?>',
            order: [[1, "desc"]],
            filterDropdown: [{name: "milestone_id", class: "w200", options: <?php echo $milestone_dropdown; ?>}],
            responsive: false, //hide responsive (+) icon
            multiSelect: [
            {
            name: "status_id",
                    text: "<?php echo app_lang('status'); ?>",
                    options: <?php echo json_encode($statuses); ?>,
                    saveSelection: true
            }
            ],
            columns: [
            {visible: false, searchable: false},
            {title: '<?php echo app_lang("id") ?>', "class": idColumnClass},
            {title: '<?php echo app_lang("title") ?>', "class": titleColumnClass},
            {visible: false, searchable: false},
            {title: '<?php echo app_lang("start_date") ?>', "iDataSort": 3, visible: showResponsiveOption},
            {visible: false, searchable: false},
            {title: '<?php echo app_lang("deadline") ?>', "iDataSort": 5, visible: showResponsiveOption},
            {title: '<?php echo app_lang("milestone") ?>', visible: showResponsiveOption},
            {visible: false, searchable: false},
            {visible: false, searchable: false},
            {visible: false, searchable: false},
            {title: '<?php echo app_lang("status") ?>', visible: showResponsiveOption}
<?php echo $custom_field_headers; ?>,
            {title: '<i data-feather="menu" class="icon-16"></i>', visible: optionVisibility, "class": "text-center option " + optionColumnClass}
            ],
            printColumns: combineCustomFieldsColumns([1, 2, 4, 7], '<?php echo $custom_field_headers; ?>'),
            xlsColumns: combineCustomFieldsColumns([1, 2, 4, 7], '<?php echo $custom_field_headers; ?>'),
            rowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            $('td:eq(0)', nRow).attr("style", "border-left:5px solid " + aData[0] + " !important;");
            }
    });
    } else {
    $("#task-table").appTable({
    source: '<?php echo_uri("projects/tasks_list_data/" . $project_id) ?>',
            order: [[1, "desc"]],
            responsive: false, //hide responsive (+) icon
            filterDropdown: [
            {name: "milestone_id", class: "w150", options: <?php echo $milestone_dropdown; ?>},
<?php if (!$show_assigned_tasks_only) { ?>{name: "assigned_to", class: "w150", options: <?php echo $assigned_to_dropdown; ?>}, <?php } ?>
            {name: "quick_filter", class: "w200", showHtml: true, options: <?php echo view("projects/tasks/quick_filters_dropdown"); ?>}
            ],
            singleDatepicker: [{name: "deadline", defaultText: "<?php echo app_lang('deadline') ?>",
                    options: [
                    {value: "expired", text: "<?php echo app_lang('expired') ?>"},
                    {value: moment().format("YYYY-MM-DD"), text: "<?php echo app_lang('today') ?>"},
                    {value: moment().add(1, 'days').format("YYYY-MM-DD"), text: "<?php echo app_lang('tomorrow') ?>"},
                    {value: moment().add(7, 'days').format("YYYY-MM-DD"), text: "<?php echo sprintf(app_lang('in_number_of_days'), 7); ?>"},
                    {value: moment().add(15, 'days').format("YYYY-MM-DD"), text: "<?php echo sprintf(app_lang('in_number_of_days'), 15); ?>"}
                    ]}],
            multiSelect: [
            {
            name: "status_id",
                    text: "<?php echo app_lang('status'); ?>",
                    options: <?php echo json_encode($statuses); ?>,
                    saveSelection: true
            }
            ],
            columns: [
            {visible: false, searchable: false},
            {title: '<?php echo app_lang("id") ?>', "class": idColumnClass},
            {title: '<?php echo app_lang("title") ?>', "class": titleColumnClass},
            {visible: false, searchable: false},
            {title: '<?php echo app_lang("start_date") ?>', "iDataSort": 3, visible: showResponsiveOption},
            {visible: false, searchable: false},
            {title: '<?php echo app_lang("deadline") ?>', "iDataSort": 5, visible: showResponsiveOption},
            {title: '<?php echo app_lang("milestone") ?>', visible: showResponsiveOption},
            {visible: false, searchable: false},
            {title: '<?php echo app_lang("assigned_to") ?>', "class": "min-w150", visible: showResponsiveOption},
            {title: '<?php echo app_lang("collaborators") ?>', visible: showResponsiveOption},
            {title: '<?php echo app_lang("status") ?>', visible: showResponsiveOption}
<?php echo $custom_field_headers; ?>,
            {title: '<i data-feather="menu" class="icon-16"></i>', visible: optionVisibility, "class": "text-center option " + optionColumnClass}
            ],
            printColumns: combineCustomFieldsColumns([1, 2, 4, 6, 8, 9, 10], '<?php echo $custom_field_headers; ?>'),
            xlsColumns: combineCustomFieldsColumns([1, 2, 4, 6, 8, 9, 10], '<?php echo $custom_field_headers; ?>'),
            rowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            $('td:eq(0)', nRow).attr("style", "border-left:5px solid " + aData[0] + " !important;");
            },
            onRelaodCallback: function () {
            hideBatchTasksBtn();
            }
    });
    }
    });
</script>

<?php echo view("projects/tasks/update_task_script"); ?>
<?php echo view("projects/tasks/update_task_read_comments_status_script"); ?>
<?php echo view("projects/tasks/quick_filters_helper_js"); ?>
