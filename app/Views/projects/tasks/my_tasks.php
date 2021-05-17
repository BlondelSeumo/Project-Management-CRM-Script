<div id="page-content" class="page-wrapper clearfix">

    <ul class="nav nav-tabs bg-white title" role="tablist">
        <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo app_lang("tasks"); ?></h4></li>

        <?php echo view("projects/tasks/tabs", array("active_tab" => "tasks_list")); ?>

        <div class="tab-title clearfix no-border">
            <div class="title-button-group">
                <?php
                if ($login_user->user_type == "staff") {
                    echo modal_anchor("", "<i data-feather='edit-2' class='icon-16'></i> " . app_lang('batch_update'), array("class" => "btn btn-info text-white hide batch-update-btn", "title" => app_lang('batch_update')));
                    echo js_anchor("<i data-feather='check-square' class='icon-16 ml15'></i> " . app_lang("batch_update"), array("class" => "btn btn-default hide batch-active-btn"));
                    echo js_anchor("<i data-feather='x' class='icon-16'></i> " . app_lang("cancel_selection"), array("class" => "hide btn btn-default batch-cancel-btn"));
                }
                if ($can_create_tasks) {
                    echo modal_anchor(get_uri("projects/task_modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_multiple_tasks'), array("class" => "btn btn-default", "title" => app_lang('add_multiple_tasks'), "data-post-add_type" => "multiple"));
                    echo modal_anchor(get_uri("projects/task_modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_task'), array("class" => "btn btn-default", "title" => app_lang('add_task')));
                }
                ?>
            </div>
        </div>

    </ul>

    <div class="card">
        <div class="table-responsive">
            <table id="task-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<?php
//if we get any task parameter, we'll show the task details modal automatically
$preview_task_id = get_array_value($_GET, 'task');
if ($preview_task_id) {
    echo modal_anchor(get_uri("projects/task_view"), "", array("id" => "preview_task_link", "title" => app_lang('task_info') . " #$preview_task_id", "data-post-id" => $preview_task_id));
}

$statuses = array();
foreach ($task_statuses as $status) {
    $is_selected = false;
    if ($status->key_name != "done") {
        $is_selected = true;
    }

    $statuses[] = array("text" => ($status->key_name ? app_lang($status->key_name) : $status->title), "value" => $status->id, "isChecked" => $is_selected);
}
?>

<script type="text/javascript">
    $(document).ready(function () {

        var showOption = true,
                idColumnClass = "w10p",
                titleColumnClass = "";

        if (isMobile()) {
            showOption = false;
            idColumnClass = "w25p";
            titleColumnClass = "w75p";
        }

        $("#task-table").appTable({
            source: '<?php echo_uri("projects/my_tasks_list_data") ?>',
            order: [[1, "desc"]],
            responsive: false, //hide responsive (+) icon
            filterDropdown: [
                {name: "specific_user_id", class: "w200", options: <?php echo $team_members_dropdown; ?>},
                {name: "milestone_id", class: "w200", options: [{id: "", text: "- <?php echo app_lang('milestone'); ?> -"}], dependency: ["project_id"], dataSource: '<?php echo_uri("projects/get_milestones_for_filter") ?>'}, //milestone is dependent on project
                {name: "project_id", class: "w200", options: <?php echo $projects_dropdown; ?>, dependent: ["milestone_id"]}, //reset milestone on changing of project
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
                    options: <?php echo json_encode($statuses); ?>
                }
            ],
            columns: [
                {visible: false, searchable: false},
                {title: '<?php echo app_lang("id") ?>', "class": idColumnClass},
                {title: '<?php echo app_lang("title") ?>', "class": titleColumnClass},
                {visible: false, searchable: false},
                {title: '<?php echo app_lang("start_date") ?>', "iDataSort": 3, visible: showOption},
                {visible: false, searchable: false},
                {title: '<?php echo app_lang("deadline") ?>', "iDataSort": 5, visible: showOption},
                {title: '<?php echo app_lang("milestone") ?>', visible: showOption},
                {title: '<?php echo app_lang("project") ?>', visible: showOption},
                {title: '<?php echo app_lang("assigned_to") ?>', "class": "min-w150", visible: showOption},
                {title: '<?php echo app_lang("collaborators") ?>', visible: showOption},
                {title: '<?php echo app_lang("status") ?>', visible: showOption}
<?php echo $custom_field_headers; ?>,
                {visible: false, searchable: false}
            ],
            printColumns: combineCustomFieldsColumns([1, 2, 4, 6, 7, 8, 9, 10], '<?php echo $custom_field_headers; ?>'),
            xlsColumns: combineCustomFieldsColumns([1, 2, 4, 6, 7, 8, 9, 10], '<?php echo $custom_field_headers; ?>'),
            rowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $('td:eq(0)', nRow).attr("style", "border-left:5px solid " + aData[0] + " !important;");
            },
            onRelaodCallback: function () {
                hideBatchTasksBtn(true);
            },
            onInitComplete: function () {
                if (!showOption) {
                    window.scrollTo(0, 210); //scroll to the content for mobile devices
                }
            }
        });


        //open task details modal automatically 

        if ($("#preview_task_link").length) {
            $("#preview_task_link").trigger("click");
        }


    });
</script>

<?php echo view("projects/tasks/batch_update/batch_update_script"); ?>
<?php echo view("projects/tasks/update_task_script"); ?>
<?php echo view("projects/tasks/update_task_read_comments_status_script"); ?>
<?php echo view("projects/tasks/quick_filters_helper_js"); ?>