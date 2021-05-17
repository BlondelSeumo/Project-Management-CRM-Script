<?php
//get the array of hidden menu
$hidden_menu = explode(",", get_setting("hidden_client_menus"));
$permissions = $login_user->permissions;
?>

<div id="keyboard-shortcut-modal-form" class="modal-body clearfix general-form white">
    <div class="container-fluid">
        <?php if (($login_user->user_type == "staff" && ($login_user->is_admin || get_array_value($permissions, "can_manage_all_projects") == "1" || get_array_value($permissions, "can_create_tasks") == "1")) || ($login_user->user_type == "client" && get_setting("client_can_create_tasks"))) { ?>
            <div class = "form-group">
                <div class="row">
                    <label for = "add_new_task" class = "col-md-10"><?php echo app_lang('add_new_task'); ?></label>
                    <div class="col-md-2">
                        <span class="badge bg-white">t</span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="add_multiple_tasks" class="col-md-10"><?php echo app_lang('add_multiple_tasks'); ?></label>
                    <div class="col-md-2">
                        <span class="badge bg-white">m</span>
                    </div>
                </div>
            </div> 
        <?php } ?>
        <?php if ($login_user->user_type == "staff") { ?>
            <div class="form-group">
                <div class="row">
                    <label for="add_project_time" class="col-md-10"><?php echo app_lang('add_project_time'); ?></label>
                    <div class="col-md-2">
                        <span class="badge bg-white">i</span>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if (get_setting("module_event") == "1" && (($login_user->user_type == "client" && !in_array("events", $hidden_menu)) || $login_user->user_type == "staff")) { ?>
            <div class="form-group">
                <div class="row">
                    <label for="add_event" class="col-md-10"><?php echo app_lang('add_event'); ?></label>
                    <div class="col-md-2">
                        <span class="badge bg-white">e</span>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if (get_setting("module_note") == "1" && $login_user->user_type == "staff") { ?>
            <div class="form-group">
                <div class="row">
                    <label for="add_note" class="col-md-10"><?php echo app_lang('add_note'); ?></label>
                    <div class="col-md-2">
                        <span class="badge bg-white">n</span>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if (get_setting("module_todo") == "1") { ?>
            <div class="form-group">
                <div class="row">
                    <label for="add_to_do" class="col-md-10"><?php echo app_lang('add_to_do'); ?></label>
                    <div class="col-md-2">
                        <span class="badge bg-white">d</span>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if (get_setting("module_ticket") == "1" && ($login_user->is_admin || get_array_value($permissions, "ticket"))) { ?>
            <div class="form-group">
                <div class="row">
                    <label for="add_ticket" class="col-md-10"><?php echo app_lang('add_ticket'); ?></label>
                    <div class="col-md-2">
                        <span class="badge bg-white">s</span>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
</div>