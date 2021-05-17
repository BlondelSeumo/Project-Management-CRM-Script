<div id="page-content" class="page-wrapper clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "client_permissions";
            echo view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <div class="card">
                <div class="page-title clearfix">
                    <h4> <?php echo app_lang('client_permissions'); ?></h4>
                </div>

                <?php echo form_open(get_uri("settings/save_client_settings"), array("id" => "client-settings-form", "class" => "general-form dashed-row", "role" => "form")); ?>
                <div class="card-body"> 
                    <div class="form-group">
                        <div class="row">
                            <label for="disable_client_signup" class="col-md-2 col-xs-8 col-sm-4"><?php echo app_lang('disable_client_signup'); ?></label>
                            <div class="col-md-10 col-xs-4 col-sm-8">
                                <?php
                                echo form_checkbox("disable_client_signup", "1", get_setting("disable_client_signup") ? true : false, "id='disable_client_signup' class='form-check-input ml15'");
                                ?>
                            </div>
                        </div>
                    </div>

                    <div id="verify_email_before_client_signup_area" class="form-group <?php echo get_setting("disable_client_signup") ? "hide" : "" ?>">
                        <div class="row">
                            <label for="verify_email_before_client_signup" class="col-md-2"><?php echo app_lang('verify_email_before_client_signup'); ?></label>
                            <div class="col-md-10">
                                <?php
                                echo form_checkbox("verify_email_before_client_signup", "1", get_setting("verify_email_before_client_signup") ? true : false, "id='verify_email_before_client_signup' class='form-check-input ml15'");
                                ?>                       
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="disable_client_login" class="col-md-2 col-xs-8 col-sm-4"><?php echo app_lang('disable_client_login'); ?></label>
                            <div class="col-md-10 col-xs-4 col-sm-8">
                                <?php
                                echo form_checkbox("disable_client_login", "1", get_setting("disable_client_login") ? true : false, "id='disable_client_login' class='form-check-input ml15'");
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="client_message_users" class=" col-md-2"><?php echo app_lang('who_can_send_or_receive_message_to_or_from_clients'); ?></label>
                            <div class=" col-md-9">
                                <?php
                                echo form_input(array(
                                    "id" => "client_message_users",
                                    "name" => "client_message_users",
                                    "value" => get_setting("client_message_users"),
                                    "class" => "form-control",
                                    "placeholder" => app_lang('team_members')
                                ));
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="hidden_client_menus" class=" col-md-2"><?php echo app_lang('hide_menus_from_client_portal'); ?></label>
                            <div class=" col-md-9">
                                <?php
                                echo form_input(array(
                                    "id" => "hidden_client_menus",
                                    "name" => "hidden_client_menus",
                                    "value" => get_setting("hidden_client_menus"),
                                    "class" => "form-control",
                                    "placeholder" => app_lang('hidden_menus')
                                ));
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="client_can_create_projects" class="col-md-2 col-xs-8 col-sm-4"><?php echo app_lang('client_can_create_projects'); ?></label>
                            <div class="col-md-10 col-xs-4 col-sm-8">
                                <?php
                                echo form_checkbox("client_can_create_projects", "1", get_setting("client_can_create_projects") ? true : false, "id='client_can_create_projects' class='form-check-input ml15'");
                                ?>                       
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="client_can_edit_projects" class="col-md-2"><?php echo app_lang('client_can_edit_projects'); ?></label>
                            <div class="col-md-10">
                                <?php
                                echo form_checkbox("client_can_edit_projects", "1", get_setting("client_can_edit_projects") ? true : false, "id='client_can_edit_projects' class='form-check-input ml15'");
                                ?>                       
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="client_can_view_tasks" class="col-md-2 col-xs-8 col-sm-4"><?php echo app_lang('client_can_view_tasks'); ?></label>

                            <div class="col-md-10 col-xs-4 col-sm-8">
                                <?php
                                echo form_checkbox("client_can_view_tasks", "1", get_setting("client_can_view_tasks") ? true : false, "id='client_can_view_tasks' class='form-check-input ml15'");
                                ?>                       
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="client_can_create_tasks" class="col-md-2 col-xs-8 col-sm-4"><?php echo app_lang('client_can_create_tasks'); ?></label>
                            <div class="col-md-10 col-xs-4 col-sm-8">
                                <?php
                                echo form_checkbox("client_can_create_tasks", "1", get_setting("client_can_create_tasks") ? true : false, "id='client_can_create_tasks' class='form-check-input ml15'");
                                ?>                       
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="client_can_edit_tasks" class="col-md-2 col-xs-8 col-sm-4"><?php echo app_lang('client_can_edit_tasks'); ?></label>
                            <div class="col-md-10 col-xs-4 col-sm-8">
                                <?php
                                echo form_checkbox("client_can_edit_tasks", "1", get_setting("client_can_edit_tasks") ? true : false, "id='client_can_edit_tasks' class='form-check-input ml15'");
                                ?>                       
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="client_can_comment_on_tasks" class="col-md-2 col-xs-8 col-sm-4"><?php echo app_lang('client_can_comment_on_tasks'); ?></label>
                            <div class="col-md-10 col-xs-4 col-sm-8">
                                <?php
                                echo form_checkbox("client_can_comment_on_tasks", "1", get_setting("client_can_comment_on_tasks") ? true : false, "id='client_can_comment_on_tasks' class='form-check-input ml15'");
                                ?>                       
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="client_can_view_project_files" class="col-md-2 col-xs-8 col-sm-4"><?php echo app_lang('client_can_view_project_files'); ?></label>
                            <div class="col-md-10 col-xs-4 col-sm-8">
                                <?php
                                echo form_checkbox("client_can_view_project_files", "1", get_setting("client_can_view_project_files") ? true : false, "id='client_can_view_project_files' class='form-check-input ml15'");
                                ?>                       
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="client_can_add_project_files" class="col-md-2 col-xs-8 col-sm-4"><?php echo app_lang('client_can_add_project_files'); ?></label>
                            <div class="col-md-10 col-xs-4 col-sm-8">
                                <?php
                                echo form_checkbox("client_can_add_project_files", "1", get_setting("client_can_add_project_files") ? true : false, "id='client_can_add_project_files' class='form-check-input ml15'");
                                ?>                       
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="client_can_comment_on_files" class="col-md-2 col-xs-8 col-sm-4"><?php echo app_lang('client_can_comment_on_files'); ?></label>
                            <div class="col-md-10 col-xs-4 col-sm-8">
                                <?php
                                echo form_checkbox("client_can_comment_on_files", "1", get_setting("client_can_comment_on_files") ? true : false, "id='client_can_comment_on_files' class='form-check-input ml15'");
                                ?>                       
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="client_can_delete_own_files_in_project" class="col-md-2 col-xs-8 col-sm-4"><?php echo app_lang('client_can_delete_own_files_in_project'); ?></label>
                            <div class="col-md-10 col-xs-4 col-sm-8">
                                <?php
                                echo form_checkbox("client_can_delete_own_files_in_project", "1", get_setting("client_can_delete_own_files_in_project") ? true : false, "id='client_can_delete_own_files_in_project' class='form-check-input ml15'");
                                ?>                       
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="client_can_view_milestones" class="col-md-2 col-xs-8 col-sm-4"><?php echo app_lang('client_can_view_milestones'); ?></label>
                            <div class="col-md-10 col-xs-4 col-sm-8">
                                <?php
                                echo form_checkbox("client_can_view_milestones", "1", get_setting("client_can_view_milestones") ? true : false, "id='client_can_view_milestones' class='form-check-input ml15'");
                                ?>                       
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="client_can_view_gantt" class="col-md-2 col-xs-8 col-sm-4"><?php echo app_lang('client_can_view_gantt'); ?></label>
                            <div class="col-md-10 col-xs-4 col-sm-8">
                                <?php
                                echo form_checkbox("client_can_view_gantt", "1", get_setting("client_can_view_gantt") ? true : false, "id='client_can_view_gantt' class='form-check-input ml15'");
                                ?>                       
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="client_can_view_overview" class="col-md-2 col-xs-8 col-sm-4"><?php echo app_lang('client_can_view_overview'); ?></label>
                            <div class="col-md-10 col-xs-4 col-sm-8">
                                <?php
                                echo form_checkbox("client_can_view_overview", "1", get_setting("client_can_view_overview") ? true : false, "id='client_can_view_overview' class='form-check-input ml15'");
                                ?>                       
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="client_message_own_contacts" class="col-md-2 col-xs-8 col-sm-4"><?php echo app_lang('client_message_own_contacts'); ?></label>
                            <div class="col-md-10 col-xs-4 col-sm-8">
                                <?php
                                echo form_checkbox("client_message_own_contacts", "1", get_setting("client_message_own_contacts") ? true : false, "id='client_message_own_contacts' class='form-check-input ml15'");
                                ?>  
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="client_can_view_activity" class="col-md-2 col-xs-8 col-sm-4"><?php echo app_lang('client_can_view_activity'); ?></label>
                            <div class="col-md-10 col-xs-4 col-sm-8">
                                <?php
                                echo form_checkbox("client_can_view_activity", "1", get_setting("client_can_view_activity") ? true : false, "id='client_can_view_activity' class='form-check-input ml15'");
                                ?>  
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="client_can_view_files" class="col-md-2 col-xs-8 col-sm-4"><?php echo app_lang('client_can_view_files'); ?> <span class="help" data-bs-toggle="tooltip" title="<?php echo app_lang('client_access_files_help_message'); ?>"><i data-feather="help-circle" class="icon-16"></i></span></label>
                            <div class="col-md-10 col-xs-4 col-sm-8">
                                <?php
                                echo form_checkbox("client_can_view_files", "1", get_setting("client_can_view_files") ? true : false, "id='client_can_view_files' class='form-check-input ml15'");
                                ?> 
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="client_can_add_files" class="col-md-2 col-xs-8 col-sm-4"><?php echo app_lang('client_can_add_files'); ?> <span class="help" data-bs-toggle="tooltip" title="<?php echo app_lang('client_access_files_help_message'); ?>"><i data-feather="help-circle" class="icon-16"></i></span></label>
                            <div class="col-md-10 col-xs-4 col-sm-8">
                                <?php
                                echo form_checkbox("client_can_add_files", "1", get_setting("client_can_add_files") ? true : false, "id='client_can_add_files' class='form-check-input ml15'");
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="disable_user_invitation_option_by_clients" class="col-md-2 col-xs-8 col-sm-4"><?php echo app_lang('disable_user_invitation_option_by_clients'); ?></label>
                            <div class="col-md-10 col-xs-4 col-sm-8">
                                <?php
                                echo form_checkbox("disable_user_invitation_option_by_clients", "1", get_setting("disable_user_invitation_option_by_clients") ? true : false, "id='disable_user_invitation_option_by_clients' class='form-check-input ml15'");
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="client_can_access_store" class="col-md-2 col-xs-8 col-sm-4"><?php echo app_lang('client_can_access_store'); ?></label>
                            <div class="col-md-10 col-xs-4 col-sm-8">
                                <?php
                                echo form_checkbox("client_can_access_store", "1", get_setting("client_can_access_store") ? true : false, "id='client_can_access_store' class='form-check-input ml15'");
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="disable_access_favorite_project_option_for_clients" class="col-md-2 col-xs-8 col-sm-4"><?php echo app_lang('disable_access_favorite_project_option_for_clients'); ?></label>
                            <div class="col-md-10 col-xs-4 col-sm-8">
                                <?php
                                echo form_checkbox("disable_access_favorite_project_option_for_clients", "1", get_setting("disable_access_favorite_project_option_for_clients") ? true : false, "id='disable_access_favorite_project_option_for_clients' class='form-check-input ml15'");
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="disable_editing_left_menu_by_clients" class="col-md-2 col-xs-8 col-sm-4"><?php echo app_lang('disable_editing_left_menu_by_clients'); ?></label>
                            <div class="col-md-10 col-xs-4 col-sm-8">
                                <?php
                                echo form_checkbox("disable_editing_left_menu_by_clients", "1", get_setting("disable_editing_left_menu_by_clients") ? true : false, "id='disable_editing_left_menu_by_clients' class='form-check-input ml15'");
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="disable_topbar_menu_customization" class="col-md-2 col-xs-8 col-sm-4"><?php echo app_lang('disable_topbar_menu_customization'); ?></label>
                            <div class="col-md-10 col-xs-4 col-sm-8">
                                <?php
                                echo form_checkbox("disable_topbar_menu_customization", "1", get_setting("disable_topbar_menu_customization") ? true : false, "id='disable_topbar_menu_customization' class='form-check-input ml15'");
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="disable_dashboard_customization_by_clients" class="col-md-2 col-xs-8 col-sm-4"><?php echo app_lang('disable_dashboard_customization_by_clients'); ?></label>
                            <div class="col-md-10 col-xs-4 col-sm-8">
                                <?php
                                echo form_checkbox("disable_dashboard_customization_by_clients", "1", get_setting("disable_dashboard_customization_by_clients") ? true : false, "id='disable_dashboard_customization_by_clients' class='form-check-input ml15'");
                                ?>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#client-settings-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });
        $("#client_message_users").select2({
            multiple: true,
            data: <?php echo ($members_dropdown); ?>
        });
        $("#hidden_client_menus").select2({
            multiple: true,
            data: <?php echo ($hidden_menu_dropdown); ?>
        });

        //show/hide email verify checkbox
        $("#disable_client_signup").click(function () {
            if ($(this).is(":checked")) {
                $("#verify_email_before_client_signup_area").addClass("hide");
            } else {
                $("#verify_email_before_client_signup_area").removeClass("hide");
            }
        });

        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>