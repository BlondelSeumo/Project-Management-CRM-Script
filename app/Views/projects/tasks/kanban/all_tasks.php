<div id="page-content" class="page-wrapper pb0 clearfix">

    <ul class="nav nav-tabs bg-white title" role="tablist">
        <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo app_lang("tasks"); ?></h4></li>

        <?php echo view("projects/tasks/tabs", array("active_tab" => "tasks_kanban")); ?>       

        <div class="tab-title clearfix no-border">
            <div class="title-button-group">
                <?php
                if ($login_user->user_type == "staff") {
                    echo modal_anchor("", "<i data-feather='edit-2' class='icon-16'></i> " . app_lang('batch_update'), array("class" => "btn btn-info text-white hide batch-update-btn", "title" => app_lang('batch_update')));
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
    <div class="bg-white kanban-filters-container">
        <div class="row">
            <div class="col-md-1 col-xs-2">
                <button class="btn btn-default" id="reload-kanban-button"><i data-feather="refresh-cw" class="icon-16"></i></button>
            </div>
            <div id="kanban-filters" class="col-md-11 col-xs-10"></div>
        </div>
    </div>

    <div id="load-kanban"></div>
</div>

<script>
    $(document).ready(function () {
        window.scrollToKanbanContent = true;
    });
</script>

<?php echo view("projects/tasks/batch_update/batch_update_script"); ?>
<?php echo view("projects/tasks/kanban/all_tasks_kanban_helper_js"); ?>
<?php echo view("projects/tasks/quick_filters_helper_js"); ?>