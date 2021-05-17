<div class="tab-content">
    <?php echo form_open(get_uri("roles/save_permissions"), array("id" => "permissions-form", "class" => "general-form dashed-row", "role" => "form")); ?>
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="card">
        <div class="card-header">
            <h4><?php echo app_lang('permissions') . ": " . $model_info->title; ?></h4>
        </div>
        <div class="card-body">

            <ul class="permission-list">
                <li>
                    <span data-feather="key" class="icon-14 ml-20"></span>
                    <h5><?php echo app_lang("set_project_permissions"); ?>:</h5>
                    <div>
                        <?php
                        echo form_checkbox("can_manage_all_projects", "1", $can_manage_all_projects ? true : false, "id='can_manage_all_projects' class='manage_project_section form-check-input'");
                        ?>
                        <label for="can_manage_all_projects"><?php echo app_lang("can_manage_all_projects"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_checkbox("can_create_projects", "1", $can_create_projects ? true : false, "id='can_create_projects' class='manage_project_section form-check-input'");
                        ?>
                        <label for="can_create_projects"><?php echo app_lang("can_create_projects"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_checkbox("can_edit_projects", "1", $can_edit_projects ? true : false, "id='can_edit_projects' class='manage_project_section form-check-input'");
                        ?>
                        <label for="can_edit_projects"><?php echo app_lang("can_edit_projects"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_checkbox("can_delete_projects", "1", $can_delete_projects ? true : false, "id='can_delete_projects' class='manage_project_section form-check-input'");
                        ?>
                        <label for="can_delete_projects"><?php echo app_lang("can_delete_projects"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_checkbox("can_add_remove_project_members", "1", $can_add_remove_project_members ? true : false, "id='can_add_remove_project_members' class='manage_project_section form-check-input'");
                        ?>
                        <label for="can_add_remove_project_members"><?php echo app_lang("can_add_remove_project_members"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_checkbox("can_create_tasks", "1", $can_create_tasks ? true : false, "id='can_create_tasks' class='manage_project_section form-check-input'");
                        ?>
                        <label for="can_create_tasks"><?php echo app_lang("can_create_tasks"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_checkbox("can_edit_tasks", "1", $can_edit_tasks ? true : false, "id='can_edit_tasks' class='form-check-input'");
                        ?>
                        <label for="can_edit_tasks"><?php echo app_lang("can_edit_tasks"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_checkbox("can_delete_tasks", "1", $can_delete_tasks ? true : false, "id='can_delete_tasks' class='form-check-input'");
                        ?>
                        <label for="can_delete_tasks"><?php echo app_lang("can_delete_tasks"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_checkbox("can_comment_on_tasks", "1", $can_comment_on_tasks ? true : false, "id='can_comment_on_tasks' class='form-check-input'");
                        ?>
                        <label for="can_comment_on_tasks"><?php echo app_lang("can_comment_on_tasks"); ?></label>
                    </div>
                    <div id="show_assigned_tasks_only_section">
                        <?php
                        echo form_checkbox("show_assigned_tasks_only", "1", $show_assigned_tasks_only ? true : false, "id='show_assigned_tasks_only' class='form-check-input'");
                        ?>
                        <label for="show_assigned_tasks_only"><?php echo app_lang("show_assigned_tasks_only"); ?></label>
                    </div>
                    <div id="can_update_only_assigned_tasks_status_section">
                        <?php
                        echo form_checkbox("can_update_only_assigned_tasks_status", "1", $can_update_only_assigned_tasks_status ? true : false, "id='can_update_only_assigned_tasks_status' class='form-check-input'");
                        ?>
                        <label for="can_update_only_assigned_tasks_status"><?php echo app_lang("can_update_only_assigned_tasks_status"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_checkbox("can_create_milestones", "1", $can_create_milestones ? true : false, "id='can_create_milestones' class='form-check-input'");
                        ?>
                        <label for="can_create_milestones"><?php echo app_lang("can_create_milestones"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_checkbox("can_edit_milestones", "1", $can_edit_milestones ? true : false, "id='can_edit_milestones' class='form-check-input'");
                        ?>
                        <label for="can_edit_milestones"><?php echo app_lang("can_edit_milestones"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_checkbox("can_delete_milestones", "1", $can_delete_milestones ? true : false, "id='can_delete_milestones' class='form-check-input'");
                        ?>
                        <label for="can_delete_milestones"><?php echo app_lang("can_delete_milestones"); ?></label>
                    </div>

                    <div>
                        <?php
                        echo form_checkbox("can_delete_files", "1", $can_delete_files ? true : false, "id='can_delete_files' class='form-check-input'");
                        ?>
                        <label for="can_delete_files"><?php echo app_lang("can_delete_files"); ?></label>
                    </div>

                </li>
                <li>
                    <span data-feather="key" class="icon-14 ml-20"></span>
                    <h5><?php echo app_lang("set_team_members_permission"); ?>:</h5>


                    <div>
                        <?php
                        echo form_checkbox("hide_team_members_list", "1", $hide_team_members_list ? true : false, "id='hide_team_members_list' class='form-check-input'");
                        ?>
                        <label for="hide_team_members_list"><?php echo app_lang("hide_team_members_list"); ?></label>
                    </div>

                    <div>
                        <?php
                        echo form_checkbox("can_view_team_members_contact_info", "1", $can_view_team_members_contact_info ? true : false, "id='can_view_team_members_contact_info' class='form-check-input'");
                        ?>
                        <label for="can_view_team_members_contact_info"><?php echo app_lang("can_view_team_members_contact_info"); ?></label>
                    </div>

                    <div>
                        <?php
                        echo form_checkbox("can_view_team_members_social_links", "1", $can_view_team_members_social_links ? true : false, "id='can_view_team_members_social_links' class='form-check-input'");
                        ?>
                        <label for="can_view_team_members_social_links"><?php echo app_lang("can_view_team_members_social_links"); ?></label>
                    </div>

                    <div>
                        <label for="can_update_team_members_general_info_and_social_links"><?php echo app_lang("can_update_team_members_general_info_and_social_links"); ?></label>
                        <div class="ml15">
                            <div>
                                <?php
                                if (is_null($team_member_update_permission)) {
                                    $team_member_update_permission = "";
                                }
                                echo form_radio(array(
                                    "id" => "team_member_update_permission_no",
                                    "name" => "team_member_update_permission",
                                    "value" => "",
                                    "class" => "team_member_update_permission toggle_specific form-check-input",
                                        ), $team_member_update_permission, ($team_member_update_permission === "") ? true : false);
                                ?>
                                <label for="team_member_update_permission_no"><?php echo app_lang("no"); ?></label>
                            </div>
                            <div>
                                <?php
                                echo form_radio(array(
                                    "id" => "team_member_update_permission_all",
                                    "name" => "team_member_update_permission",
                                    "value" => "all",
                                    "class" => "team_member_update_permission toggle_specific form-check-input",
                                        ), $team_member_update_permission, ($team_member_update_permission === "all") ? true : false);
                                ?>
                                <label for="team_member_update_permission_all"><?php echo app_lang("yes_all_members"); ?></label>
                            </div>
                            <div class="form-group">
                                <?php
                                echo form_radio(array(
                                    "id" => "team_member_update_permission_specific",
                                    "name" => "team_member_update_permission",
                                    "value" => "specific",
                                    "class" => "team_member_update_permission toggle_specific form-check-input",
                                        ), $team_member_update_permission, ($team_member_update_permission === "specific") ? true : false);
                                ?>
                                <label for="team_member_update_permission_specific"><?php echo app_lang("yes_specific_members_or_teams"); ?>:</label>
                                <div class="specific_dropdown">
                                    <input type="text" value="<?php echo $team_member_update_permission_specific; ?>" name="team_member_update_permission_specific" id="team_member_update_permission_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo app_lang('field_required'); ?>" placeholder="<?php echo app_lang('choose_members_and_or_teams'); ?>"  />    
                                </div>
                            </div>
                        </div>
                    </div>

                </li>

                <li>
                    <span data-feather="key" class="icon-14 ml-20"></span>
                    <h5><?php echo app_lang("set_message_permissions"); ?>:</h5>
                    <div>
                        <?php
                        echo form_checkbox("message_permission_no", "1", ($message_permission == "no") ? true : false, "id='message_permission_no' class='form-check-input'");
                        ?>
                        <label for="message_permission_no"><?php echo app_lang("cant_send_any_messages"); ?></label>
                    </div>
                    <div id="message_permission_specific_area" class="form-group <?php echo ($message_permission == "no") ? "hide" : ""; ?>">
                        <?php
                        echo form_checkbox("message_permission_specific_checkbox", "1", ($message_permission == "specific") ? true : false, "id='message_permission_specific_checkbox' class='message_permission_specific toggle_specific form-check-input'");
                        ?>
                        <label for="message_permission_specific_checkbox"><?php echo app_lang("can_send_messages_to_specific_members_or_teams"); ?></label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo $message_permission_specific; ?>" name="message_permission_specific" id="message_permission_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo app_lang('field_required'); ?>" placeholder="<?php echo app_lang('choose_members_and_or_teams'); ?>"  />    
                        </div>
                    </div>
                </li>

                <li>
                    <span data-feather="key" class="icon-14 ml-20"></span>
                    <h5><?php echo app_lang("set_event_permissions"); ?>:</h5>
                    <div>
                        <?php
                        echo form_checkbox("disable_event_sharing", "1", $disable_event_sharing ? true : false, "id='disable_event_sharing' class='form-check-input'");
                        ?>
                        <label for="disable_event_sharing"><?php echo app_lang("disable_event_sharing"); ?></label>
                    </div>
                </li>

                <li>
                    <span data-feather="key" class="icon-14 ml-20"></span>
                    <h5><?php echo app_lang("can_manage_team_members_leave"); ?> <span class="help" data-bs-toggle="tooltip" title="Assign, approve or reject leave applications"><span data-feather="help-circle" class="icon-14"></span></span> </h5>
                    <div>
                        <?php
                        if (is_null($leave)) {
                            $leave = "";
                        }
                        echo form_radio(array(
                            "id" => "leave_permission_no",
                            "name" => "leave_permission",
                            "value" => "",
                            "class" => "leave_permission toggle_specific form-check-input",
                                ), $leave, ($leave === "") ? true : false);
                        ?>
                        <label for="leave_permission_no"><?php echo app_lang("no"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "leave_permission_all",
                            "name" => "leave_permission",
                            "value" => "all",
                            "class" => "leave_permission toggle_specific form-check-input",
                                ), $leave, ($leave === "all") ? true : false);
                        ?>
                        <label for="leave_permission_all"><?php echo app_lang("yes_all_members"); ?></label>
                    </div>
                    <div class="form-group pb0 mb0 no-border">
                        <?php
                        echo form_radio(array(
                            "id" => "leave_permission_specific",
                            "name" => "leave_permission",
                            "value" => "specific",
                            "class" => "leave_permission toggle_specific form-check-input",
                                ), $leave, ($leave === "specific") ? true : false);
                        ?>
                        <label for="leave_permission_specific"><?php echo app_lang("yes_specific_members_or_teams") . " (" . app_lang("excluding_his_her_leaves") . ")"; ?>:</label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo $leave_specific; ?>" name="leave_permission_specific" id="leave_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo app_lang('field_required'); ?>" placeholder="<?php echo app_lang('choose_members_and_or_teams'); ?>"  />    
                        </div>

                    </div>
                    <div class="form-group">
                        <div>
                            <?php
                            echo form_checkbox("can_delete_leave_application", "1", $can_delete_leave_application ? true : false, "id='can_delete_leave_application' class='form-check-input'");
                            ?>
                            <label for="can_delete_leave_application"><?php echo app_lang("can_delete_leave_application"); ?> <span class="help" data-bs-toggle="tooltip" title="Can delete based on his/her access permission"><i data-feather="help-circle" class="icon-14"></i></span></label>
                        </div>
                    </div>
                </li>
                <li>
                    <span data-feather="key" class="icon-14 ml-20"></span>
                    <h5><?php echo app_lang("can_manage_team_members_timecards"); ?> <span class="help" data-bs-toggle="tooltip" title="Add, edit and delete time cards"><i data-feather="help-circle" class="icon-14"></i></span></h5>
                    <div>
                        <?php
                        if (is_null($attendance)) {
                            $attendance = "";
                        }
                        echo form_radio(array(
                            "id" => "attendance_permission_no",
                            "name" => "attendance_permission",
                            "value" => "",
                            "class" => "attendance_permission toggle_specific form-check-input",
                                ), $attendance, ($attendance === "") ? true : false);
                        ?>
                        <label for="attendance_permission_no"><?php echo app_lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "attendance_permission_all",
                            "name" => "attendance_permission",
                            "value" => "all",
                            "class" => "attendance_permission toggle_specific form-check-input",
                                ), $attendance, ($attendance === "all") ? true : false);
                        ?>
                        <label for="attendance_permission_all"><?php echo app_lang("yes_all_members"); ?></label>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_radio(array(
                            "id" => "attendance_permission_specific",
                            "name" => "attendance_permission",
                            "value" => "specific",
                            "class" => "attendance_permission toggle_specific form-check-input",
                                ), $attendance, ($attendance === "specific") ? true : false);
                        ?>
                        <label for="attendance_permission_specific"><?php echo app_lang("yes_specific_members_or_teams") . " (" . app_lang("excluding_his_her_time_cards") . ")"; ?>:</label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo $attendance_specific; ?>" name="attendance_permission_specific" id="attendance_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo app_lang('field_required'); ?>" placeholder="<?php echo app_lang('choose_members_and_or_teams'); ?>"  />
                        </div>
                    </div>

                </li>

                <li>
                    <span data-feather="key" class="icon-14 ml-20"></span>
                    <h5><?php echo app_lang("can_manage_team_members_project_timesheet"); ?></h5>
                    <div>
                        <?php
                        if (is_null($timesheet_manage_permission)) {
                            $timesheet_manage_permission = "";
                        }
                        echo form_radio(array(
                            "id" => "timesheet_manage_permission_no",
                            "name" => "timesheet_manage_permission",
                            "value" => "",
                            "class" => "timesheet_manage_permission toggle_specific form-check-input",
                                ), $timesheet_manage_permission, ($timesheet_manage_permission === "") ? true : false);
                        ?>
                        <label for="timesheet_manage_permission_no"><?php echo app_lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "timesheet_manage_permission_all",
                            "name" => "timesheet_manage_permission",
                            "value" => "all",
                            "class" => "timesheet_manage_permission toggle_specific form-check-input",
                                ), $timesheet_manage_permission, ($timesheet_manage_permission === "all") ? true : false);
                        ?>
                        <label for="timesheet_manage_permission_all"><?php echo app_lang("yes_all_members"); ?></label>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_radio(array(
                            "id" => "timesheet_manage_permission_specific",
                            "name" => "timesheet_manage_permission",
                            "value" => "specific",
                            "class" => "timesheet_manage_permission toggle_specific form-check-input",
                                ), $timesheet_manage_permission, ($timesheet_manage_permission === "specific") ? true : false);
                        ?>
                        <label for="timesheet_manage_permission_specific"><?php echo app_lang("yes_specific_members_or_teams"); ?>:</label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo $timesheet_manage_permission_specific; ?>" name="timesheet_manage_permission_specific" id="timesheet_manage_permission_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo app_lang('field_required'); ?>" placeholder="<?php echo app_lang('choose_members_and_or_teams'); ?>"  />
                        </div>
                    </div>
                </li>


                <li>
                    <span data-feather="key" class="icon-14 ml-20"></span>
                    <h5><?php echo app_lang("can_access_invoices"); ?></h5>
                    <div>
                        <?php
                        if (is_null($invoice)) {
                            $invoice = "";
                        }
                        echo form_radio(array(
                            "id" => "invoice_no",
                            "name" => "invoice_permission",
                            "value" => "",
                            "class" => "form-check-input",
                                ), $invoice, ($invoice === "") ? true : false);
                        ?>
                        <label for="invoice_no"><?php echo app_lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "invoice_yes",
                            "name" => "invoice_permission",
                            "value" => "all",
                            "class" => "form-check-input",
                                ), $invoice, ($invoice === "all") ? true : false);
                        ?>
                        <label for="invoice_yes"><?php echo app_lang("yes"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "invoice_read_only",
                            "name" => "invoice_permission",
                            "value" => "read_only",
                            "class" => "form-check-input",
                                ), $invoice, ($invoice === "read_only") ? true : false);
                        ?>
                        <label for="invoice_read_only"><?php echo app_lang("read_only"); ?></label>
                    </div>
                </li>
                <li>
                    <span data-feather="key" class="icon-14 ml-20"></span>
                    <h5><?php echo app_lang("can_access_estimates"); ?></h5>
                    <div>
                        <?php
                        if (is_null($estimate)) {
                            $estimate = "";
                        }
                        echo form_radio(array(
                            "id" => "estimate_no",
                            "name" => "estimate_permission",
                            "value" => "",
                            "class" => "form-check-input",
                                ), $estimate, ($estimate === "") ? true : false);
                        ?>
                        <label for="estimate_no"><?php echo app_lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "estimate_yes",
                            "name" => "estimate_permission",
                            "value" => "all",
                            "class" => "form-check-input",
                                ), $estimate, ($estimate === "all") ? true : false);
                        ?>
                        <label for="estimate_yes"><?php echo app_lang("yes"); ?></label>
                    </div>
                </li>
                <li>
                    <span data-feather="key" class="icon-14 ml-20"></span>
                    <h5><?php echo app_lang("can_access_expenses"); ?></h5>
                    <div>
                        <?php
                        if (is_null($expense)) {
                            $expense = "";
                        }
                        echo form_radio(array(
                            "id" => "expense_no",
                            "name" => "expense_permission",
                            "value" => "",
                            "class" => "form-check-input",
                                ), $expense, ($expense === "") ? true : false);
                        ?>
                        <label for="expense_no"><?php echo app_lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "expense_yes",
                            "name" => "expense_permission",
                            "value" => "all",
                            "class" => "form-check-input",
                                ), $expense, ($expense === "all") ? true : false);
                        ?>
                        <label for="expense_yes"><?php echo app_lang("yes"); ?></label>
                    </div>
                </li>
                <li>
                    <span data-feather="key" class="icon-14 ml-20"></span>
                    <h5><?php echo app_lang("can_access_clients_information"); ?> <span class="help" data-bs-toggle="tooltip" title="Hides all information of clients except company name."><i data-feather="help-circle" class="icon-14"></i></span></h5>
                    <div>
                        <?php
                        if (is_null($client)) {
                            $client = "";
                        }
                        echo form_radio(array(
                            "id" => "client_no",
                            "name" => "client_permission",
                            "value" => "",
                            "class" => "form-check-input",
                                ), $client, ($client === "") ? true : false);
                        ?>
                        <label for="client_no"><?php echo app_lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "client_yes",
                            "name" => "client_permission",
                            "value" => "all",
                            "class" => "form-check-input",
                                ), $client, ($client === "all") ? true : false);
                        ?>
                        <label for="client_yes"><?php echo app_lang("yes_all_clients"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "client_yes_own",
                            "name" => "client_permission",
                            "value" => "own",
                            "class" => "form-check-input",
                                ), $client, ($client === "own") ? true : false);
                        ?>
                        <label for="client_yes_own"><?php echo app_lang("yes_only_own_clients"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "client_read_only",
                            "name" => "client_permission",
                            "value" => "read_only",
                            "class" => "form-check-input",
                                ), $client, ($client === "read_only") ? true : false);
                        ?>
                        <label for="client_read_only"><?php echo app_lang("read_only"); ?></label>
                    </div>
                </li>
                <li>
                    <span data-feather="key" class="icon-14 ml-20"></span>
                    <h5><?php echo app_lang("can_access_leads_information"); ?></h5>
                    <div>
                        <?php
                        if (is_null($lead)) {
                            $lead = "";
                        }
                        echo form_radio(array(
                            "id" => "lead_no",
                            "name" => "lead_permission",
                            "value" => "",
                            "class" => "form-check-input",
                                ), $lead, ($lead === "") ? true : false);
                        ?>
                        <label for="lead_no"><?php echo app_lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "lead_yes",
                            "name" => "lead_permission",
                            "value" => "all",
                            "class" => "form-check-input",
                                ), $lead, ($lead === "all") ? true : false);
                        ?>
                        <label for="lead_yes"><?php echo app_lang("yes_all_leads"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "lead_yes_own",
                            "name" => "lead_permission",
                            "value" => "own",
                            "class" => "form-check-input",
                                ), $lead, ($lead === "own") ? true : false);
                        ?>
                        <label for="lead_yes_own"><?php echo app_lang("yes_only_own_leads"); ?></label>
                    </div>
                </li>
                <li>
                    <span data-feather="key" class="icon-14 ml-20"></span>
                    <h5><?php echo app_lang("can_access_tickets"); ?></h5>       
                    <div>
                        <?php
                        if (is_null($ticket)) {
                            $ticket = "";
                        }
                        echo form_radio(array(
                            "id" => "ticket_permission_no",
                            "name" => "ticket_permission",
                            "value" => "",
                            "class" => "ticket_permission toggle_specific form-check-input",
                                ), $ticket, ($ticket === "") ? true : false);
                        ?>
                        <label for="ticket_permission_no"><?php echo app_lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "ticket_permission_all",
                            "name" => "ticket_permission",
                            "value" => "all",
                            "class" => "ticket_permission toggle_specific form-check-input",
                                ), $ticket, ($ticket === "all") ? true : false);
                        ?>
                        <label for="ticket_permission_all"><?php echo app_lang("yes_all_tickets"); ?></label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "ticket_permission_assigned_only",
                            "name" => "ticket_permission",
                            "value" => "assigned_only",
                            "class" => "ticket_permission toggle_specific form-check-input",
                                ), $ticket, ($ticket === "assigned_only") ? true : false);
                        ?>
                        <label for="ticket_permission_assigned_only"><?php echo app_lang("yes_assigned_tickets_only"); ?></label>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_radio(array(
                            "id" => "ticket_permission_specific",
                            "name" => "ticket_permission",
                            "value" => "specific",
                            "class" => "ticket_permission toggle_specific form-check-input",
                                ), $ticket, ($ticket === "specific") ? true : false);
                        ?>
                        <label for="ticket_permission_specific"><?php echo app_lang("yes_specific_ticket_types"); ?>:</label>
                        <div class="specific_dropdown">
                            <input type="text" value="<?php echo $ticket_specific; ?>" name="ticket_permission_specific" id="ticket_types_specific_dropdown" class="w100p validate-hidden"  data-rule-required="true" data-msg-required="<?php echo app_lang('field_required'); ?>" placeholder="<?php echo app_lang('choose_ticket_types'); ?>"  />
                        </div>
                    </div>
                </li>
                <li>
                    <span data-feather="key" class="icon-14 ml-20"></span>
                    <h5><?php echo app_lang("can_manage_announcements"); ?></h5>
                    <div>
                        <?php
                        if (is_null($announcement)) {
                            $announcement = "";
                        }
                        echo form_radio(array(
                            "id" => "announcement_no",
                            "name" => "announcement_permission",
                            "value" => "",
                            "class" => "form-check-input",
                                ), $announcement, ($announcement === "") ? true : false);
                        ?>
                        <label for="announcement_no"><?php echo app_lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "announcement_yes",
                            "name" => "announcement_permission",
                            "value" => "all",
                            "class" => "form-check-input",
                                ), $announcement, ($announcement === "all") ? true : false);
                        ?>
                        <label for="announcement_yes"><?php echo app_lang("yes"); ?></label>
                    </div>
                </li>
                <li>
                    <span data-feather="key" class="icon-14 ml-20"></span>
                    <h5><?php echo app_lang("can_access_orders"); ?></h5>
                    <div>
                        <?php
                        if (is_null($order)) {
                            $order = "";
                        }
                        echo form_radio(array(
                            "id" => "order_no",
                            "name" => "order_permission",
                            "value" => "",
                            "class" => "form-check-input",
                                ), $order, ($order === "") ? true : false);
                        ?>
                        <label for="order_no"><?php echo app_lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "order_yes",
                            "name" => "order_permission",
                            "value" => "all",
                            "class" => "form-check-input",
                                ), $order, ($order === "all") ? true : false);
                        ?>
                        <label for="order_yes"><?php echo app_lang("yes"); ?></label>
                    </div>
                </li>
                <li>
                    <span data-feather="key" class="icon-14 ml-20"></span>
                    <h5><?php echo app_lang("can_manage_help_and_knowledge_base"); ?></h5>
                    <div>
                        <?php
                        if (is_null($help_and_knowledge_base)) {
                            $help_and_knowledge_base = "";
                        }
                        echo form_radio(array(
                            "id" => "help_no",
                            "name" => "help_and_knowledge_base",
                            "value" => "",
                            "class" => "form-check-input",
                                ), $help_and_knowledge_base, ($help_and_knowledge_base === "") ? true : false);
                        ?>
                        <label for="help_no"><?php echo app_lang("no"); ?> </label>
                    </div>
                    <div>
                        <?php
                        echo form_radio(array(
                            "id" => "help_yes",
                            "name" => "help_and_knowledge_base",
                            "value" => "all",
                            "class" => "form-check-input",
                                ), $help_and_knowledge_base, ($help_and_knowledge_base === "all") ? true : false);
                        ?>
                        <label for="help_yes"><?php echo app_lang("yes"); ?></label>
                    </div>
                </li>

            </ul>

        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary mr10"><span data-feather="check-circle" class="icon-14"></span> <?php echo app_lang('save'); ?></button>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#permissions-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });

        $("#leave_specific_dropdown, #attendance_specific_dropdown, #timesheet_manage_permission_specific_dropdown,  #team_member_update_permission_specific_dropdown, #message_permission_specific_dropdown").select2({
            multiple: true,
            formatResult: teamAndMemberSelect2Format,
            formatSelection: teamAndMemberSelect2Format,
            data: <?php echo ($members_and_teams_dropdown); ?>
        });

        $("#ticket_types_specific_dropdown").select2({
            multiple: true,
            data: <?php echo ($ticket_types_dropdown); ?>
        });

        $('[data-bs-toggle="tooltip"]').tooltip();

        $(".toggle_specific").click(function () {
            toggle_specific_dropdown();
        });

        toggle_specific_dropdown();

        function toggle_specific_dropdown() {
            var selectors = [".leave_permission", ".attendance_permission", ".timesheet_manage_permission", ".team_member_update_permission", ".ticket_permission", ".message_permission_specific"];
            $.each(selectors, function (index, element) {
                var $element = $(element + ":checked");
                if ((element !== ".message_permission_specific" && $element.val() === "specific") || (element === ".message_permission_specific" && $element.is(":checked") && !$("#message_permission_specific_area").hasClass("hide"))) {
                    $element.closest("li").find(".specific_dropdown").show().find("input").addClass("validate-hidden");
                } else {
                    //console.log($element.closest("li").find(".specific_dropdown"));
                    $(element).closest("li").find(".specific_dropdown").hide().find("input").removeClass("validate-hidden");
                }
            });

        }

        //show/hide message permission checkbox
        $("#message_permission_no").click(function () {
            if ($(this).is(":checked")) {
                $("#message_permission_specific_area").addClass("hide");
            } else {
                $("#message_permission_specific_area").removeClass("hide");
            }

            toggle_specific_dropdown();
        });

        var manageProjectSection = "#can_manage_all_projects, #can_create_projects, #can_edit_projects, #can_delete_projects, #can_add_remove_project_members, #can_create_tasks";
        var manageAssignedTasks = "#show_assigned_tasks_only, #can_update_only_assigned_tasks_status";
        var manageAssignedTasksSection = "#show_assigned_tasks_only_section, #can_update_only_assigned_tasks_status_section";

        if ($(manageProjectSection).is(':checked')) {
            $(manageAssignedTasksSection).addClass("hide");
        }

        $(manageProjectSection).click(function () {
            if ($(this).is(":checked")) {
                $(manageAssignedTasks).prop("checked", false);
                $(manageAssignedTasksSection).addClass("hide");
            }
        });

        $('.manage_project_section').change(function () {
            var checkedStatus = $('.manage_project_section:checkbox:checked').length > 0;
            if (!checkedStatus) {
                $(manageAssignedTasksSection).removeClass("hide");
            }
        }).change();

    });
</script>