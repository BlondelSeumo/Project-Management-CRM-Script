<?php
if (!function_exists("make_project_tabs_data")) {

    function make_project_tabs_data($default_project_tabs = array(), $is_client = false) {
        $project_tab_order = get_setting("project_tab_order");
        $project_tab_order_of_clients = get_setting("project_tab_order_of_clients");
        $custom_project_tabs = array();

        if ($is_client && $project_tab_order_of_clients) {
            //user is client
            $custom_project_tabs = explode(',', $project_tab_order_of_clients);
        } else if (!$is_client && $project_tab_order) {
            //user is team member
            $custom_project_tabs = explode(',', $project_tab_order);
        }

        $final_projects_tabs = array();
        if ($custom_project_tabs) {
            foreach ($custom_project_tabs as $custom_project_tab) {
                if (array_key_exists($custom_project_tab, $default_project_tabs)) {
                    $final_projects_tabs[$custom_project_tab] = get_array_value($default_project_tabs, $custom_project_tab);
                }
            }
        }

        $final_projects_tabs = $final_projects_tabs ? $final_projects_tabs : $default_project_tabs;

        foreach ($final_projects_tabs as $key => $value) {
            echo "<li class='nav-item' role='presentation'><a class='nav-link' data-bs-toggle='tab' href='" . get_uri($value) . "' data-bs-target='#project-$key-section'>" . app_lang($key) . "</a></li>";
        }
    }

}
?>

<div class="page-content clearfix">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="page-title no-bg clearfix mb5 no-border">
                    <div>
                        <h1 class="pl0">
                            <?php if ($project_info->status == "open") { ?>
                                <span title="<?php echo app_lang("open"); ?>"><i data-feather="grid" class='icon'></i></span>
                            <?php } else if ($project_info->status == "completed") { ?>
                                <span title="<?php echo app_lang("completed"); ?>"><i data-feather="check-circle" class='icon'></i></span>
                            <?php } else if ($project_info->status == "hold") { ?>
                                <span title="<?php echo app_lang("hold"); ?>"><i data-feather="pause-circle" class='icon'></i></span>
                            <?php } else if ($project_info->status == "canceled") { ?>
                                <span title="<?php echo app_lang("canceled"); ?>"><i data-feather="x-circle" class='icon'></i></span>
                            <?php } ?>

                            <?php echo $project_info->title; ?>

                            <?php if (!(get_setting("disable_access_favorite_project_option_for_clients") && $login_user->user_type == "client")) { ?>
                                <span id="star-mark">
                                    <?php
                                    if ($is_starred) {
                                        echo view('projects/star/starred', array("project_id" => $project_info->id));
                                    } else {
                                        echo view('projects/star/not_starred', array("project_id" => $project_info->id));
                                    }
                                    ?>
                                </span>
                            <?php } ?>
                        </h1>
                    </div>

                    <div class="project-title-button-group-section">
                        <div class="title-button-group mr0" id="project-timer-box">
                            <?php echo view("projects/project_title_buttons"); ?>
                        </div>
                    </div>
                </div>
                <ul id="project-tabs" data-bs-toggle="ajax-tab" class="nav nav-tabs rounded classic mb20 scrollable-tabs border-white" role="tablist">
                    <?php
                    if ($login_user->user_type === "staff") {
                        //default tab order
                        $project_tabs = array(
                            "overview" => "projects/overview/" . $project_info->id,
                            "tasks_list" => "projects/tasks/" . $project_info->id,
                            "tasks_kanban" => "projects/tasks_kanban/" . $project_info->id,
                        );

                        if ($show_milestone_info) {
                            $project_tabs["milestones"] = "projects/milestones/" . $project_info->id;
                        }

                        if ($show_gantt_info) {
                            $project_tabs["gantt"] = "projects/gantt/" . $project_info->id;
                        }

                        if ($show_note_info) {
                            $project_tabs["notes"] = "projects/notes/" . $project_info->id;
                        }

                        $project_tabs["files"] = "projects/files/" . $project_info->id;
                        $project_tabs["comments"] = "projects/comments/" . $project_info->id;
                        $project_tabs["customer_feedback"] = "projects/customer_feedback/" . $project_info->id;

                        if ($show_timesheet_info) {
                            $project_tabs["timesheets"] = "projects/timesheets/" . $project_info->id;
                        }

                        if ($show_invoice_info) {
                            $project_tabs["invoices"] = "projects/invoices/" . $project_info->id;
                            $project_tabs["payments"] = "projects/payments/" . $project_info->id;
                        }

                        if ($show_expense_info) {
                            $project_tabs["expenses"] = "projects/expenses/" . $project_info->id;
                        }

                        make_project_tabs_data($project_tabs);
                    } else {
                        //default tab order
                        $project_tabs = array(
                            "overview" => "projects/overview_for_client/" . $project_info->id
                        );

                        if ($show_tasks) {
                            $project_tabs["tasks_list"] = "projects/tasks/" . $project_info->id;
                            $project_tabs["tasks_kanban"] = "projects/tasks_kanban/" . $project_info->id;
                        }

                        if ($show_files) {
                            $project_tabs["files"] = "projects/files/" . $project_info->id;
                        }

                        $project_tabs["comments"] = "projects/customer_feedback/" . $project_info->id;

                        if ($show_milestone_info) {
                            $project_tabs["milestones"] = "projects/milestones/" . $project_info->id;
                        }

                        if ($show_gantt_info) {
                            $project_tabs["gantt"] = "projects/gantt/" . $project_info->id;
                        }

                        if ($show_timesheet_info) {
                            $project_tabs["timesheets"] = "projects/timesheets/" . $project_info->id;
                        }

                        make_project_tabs_data($project_tabs, true);
                    }
                    ?>

                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade active" id="project-overview-section"></div>
                    <div role="tabpanel" class="tab-pane fade" id="project-tasks_list-section"></div>
                    <div role="tabpanel" class="tab-pane fade" id="project-tasks_kanban-section"></div>
                    <div role="tabpanel" class="tab-pane fade" id="project-milestones-section"></div>
                    <div role="tabpanel" class="tab-pane fade" id="project-gantt-section"></div>
                    <div role="tabpanel" class="tab-pane fade" id="project-files-section"></div>
                    <div role="tabpanel" class="tab-pane fade" id="project-comments-section"></div>
                    <div role="tabpanel" class="tab-pane fade" id="project-customer_feedback-section"></div>
                    <div role="tabpanel" class="tab-pane fade" id="project-notes-section"></div>
                    <div role="tabpanel" class="tab-pane fade" id="project-timesheets-section"></div>
                    <div role="tabpanel" class="tab-pane fade" id="project-invoices-section"></div>
                    <div role="tabpanel" class="tab-pane fade" id="project-payments-section"></div>
                    <div role="tabpanel" class="tab-pane fade" id="project-expenses-section"></div>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="project-footer-button-section">
    <?php echo view("projects/project_title_buttons"); ?>
</div>

<?php
//if we get any task parameter, we'll show the task details modal automatically
$preview_task_id = get_array_value($_GET, 'task');
if ($preview_task_id) {
    echo modal_anchor(get_uri("projects/task_view"), "", array("id" => "preview_task_link", "title" => app_lang('task_info') . " #$preview_task_id", "data-post-id" => $preview_task_id, "data-modal-lg" => "1"));
}
?>

<?php
load_css(array(
    "assets/js/gantt-chart/frappe-gantt.css",
));
load_js(array(
    "assets/js/gantt-chart/frappe-gantt.js",
));
?>

<script type="text/javascript">
    RELOAD_PROJECT_VIEW_AFTER_UPDATE = true;

    $(document).ready(function () {
        setTimeout(function () {
            var tab = "<?php echo $tab; ?>";
            if (tab === "comment") {
                $("[data-bs-target='#project-comments-section']").trigger("click");
            } else if (tab === "customer_feedback") {
                $("[data-bs-target='#project-customer_feedback-section']").trigger("click");
            } else if (tab === "files") {
                $("[data-bs-target='#project-files-section']").trigger("click");
            } else if (tab === "gantt") {
                $("[data-bs-target='#project-gantt-section']").trigger("click");
            } else if (tab === "tasks") {
                $("[data-bs-target='#project-tasks_list-section']").trigger("click");
            } else if (tab === "tasks_kanban") {
                $("[data-bs-target='#project-tasks_kanban-section']").trigger("click");
            } else if (tab === "milestones") {
                $("[data-bs-target='#project-milestones-section']").trigger("click");
            }
        }, 210);


        //open task details modal automatically 

        if ($("#preview_task_link").length) {
            $("#preview_task_link").trigger("click");
        }

    });
</script>

<?php echo view("projects/tasks/batch_update/batch_update_script"); ?>