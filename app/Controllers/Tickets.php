<?php

namespace App\Controllers;

class Tickets extends Security_Controller {

    protected $Ticket_templates_model;

    function __construct() {
        parent::__construct();
        $this->init_permission_checker("ticket");

        $this->Ticket_templates_model = model('App\Models\Ticket_templates_model');
    }

    //only admin can delete tickets
    protected function can_delete_tickets() {
        return $this->login_user->is_admin;
    }

    // load ticket list view
    function index($status = "") {
        $this->check_module_availability("module_ticket");

        $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("tickets", $this->login_user->is_admin, $this->login_user->user_type);

        $view_data['show_project_reference'] = get_setting('project_reference_in_tickets');

        $view_data['status'] = $status;

        if ($this->login_user->user_type === "staff") {

            //prepare ticket label filter list
            $view_data['ticket_labels_dropdown'] = json_encode($this->make_labels_dropdown("ticket", "", true));

            //prepare assign to filter list
            $assigned_to_dropdown = array(array("id" => "", "text" => "- " . app_lang("assigned_to") . " -"));

            $assigned_to_list = $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", array("deleted" => 0, "user_type" => "staff"));
            foreach ($assigned_to_list as $key => $value) {
                $assigned_to_dropdown[] = array("id" => $key, "text" => $value);
            }

            $view_data['show_options_column'] = true; //team members can view the options column

            $view_data['assigned_to_dropdown'] = json_encode($assigned_to_dropdown);

            $view_data['ticket_types_dropdown'] = json_encode($this->_get_ticket_types_dropdown_list_for_filter());

            return $this->template->rander("tickets/tickets_list", $view_data);
        } else {
            $view_data['client_id'] = $this->login_user->client_id;
            $view_data['page_type'] = "full";
            return $this->template->rander("clients/tickets/index", $view_data);
        }
    }

    //load new tickt modal 
    function modal_form() {
        $this->validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->request->getPost('id');
        $this->_check_permission_of_selected_ticket($id);

        //client should not be able to edit ticket
        if ($this->login_user->user_type === "client" && $id) {
            app_redirect("forbidden");
        }

        $where = array();
        if ($this->login_user->user_type === "staff" && $this->access_type !== "all" && $this->access_type !== "assigned_only") {
            $where = array("where_in" => array("id" => $this->allowed_ticket_types));
        }

        $ticket_info = $this->Tickets_model->get_one($this->request->getPost("id"));

        $projects = $this->Projects_model->get_dropdown_list(array("title"), "id", array("client_id" => $ticket_info->client_id));
        if ($this->login_user->user_type == "client") {
            $projects = $this->Projects_model->get_dropdown_list(array("title"), "id", array("client_id" => $this->login_user->client_id));
            $ticket_info->client_id = $this->login_user->client_id;
        }
        $suggestion = array(array("id" => "", "text" => "-"));
        foreach ($projects as $key => $value) {
            $suggestion[] = array("id" => $key, "text" => $value);
        }



        $view_data['projects_suggestion'] = $suggestion;

        $view_data['ticket_types_dropdown'] = $this->Ticket_types_model->get_dropdown_list(array("title"), "id", $where);

        $view_data['model_info'] = $this->Tickets_model->get_one($id);
        $view_data['client_id'] = $ticket_info->client_id;
        $view_data['clients_dropdown'] = array("" => "-") + $this->Clients_model->get_dropdown_list(array("company_name"), "id", array("is_lead" => 0));
        $view_data['show_project_reference'] = get_setting('project_reference_in_tickets');

        $view_data['project_id'] = $this->request->getPost('project_id');

        $view_data['requested_by_id'] = $ticket_info->requested_by;

        if ($this->login_user->user_type == "client") {
            $view_data['project_id'] = $this->request->getPost('project_id');
        } else {
            $view_data['projects_dropdown'] = $this->Projects_model->get_dropdown_list(array("title"));
        }

        $requested_by_suggestion = array(array("id" => "", "text" => "-"));
        $view_data['requested_by_dropdown'] = $requested_by_suggestion;

        //prepare assign to list
        $assigned_to_dropdown = array("" => "-") + $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", array("deleted" => 0, "user_type" => "staff"));
        $view_data['assigned_to_dropdown'] = $assigned_to_dropdown;

        //prepare label suggestions
        $view_data['label_suggestions'] = $this->make_labels_dropdown("ticket", $view_data['model_info']->labels);


        $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("tickets", $view_data['model_info']->id, $this->login_user->is_admin, $this->login_user->user_type)->getResult();

        return $this->template->view('tickets/modal_form', $view_data);
    }

    //get project suggestion against client
    function get_project_suggestion($client_id = 0) {

        $this->access_only_allowed_members();

        $projects = $this->Projects_model->get_dropdown_list(array("title"), "id", array("client_id" => $client_id));
        $suggestion = array(array("id" => "", "text" => "-"));
        foreach ($projects as $key => $value) {
            $suggestion[] = array("id" => $key, "text" => $value);
        }
        echo json_encode($suggestion);
    }

    // add a new ticket
    function save() {
        $id = $this->request->getPost('id');
        $this->_check_permission_of_selected_ticket($id);

        if ($id) {
            $this->validate_submitted_data(array(
                "ticket_type_id" => "required|numeric"
            ));
        } else {
            $this->validate_submitted_data(array(
                "client_id" => "required|numeric",
                "ticket_type_id" => "required|numeric"
            ));
        }


        $client_id = $this->request->getPost('client_id');

        $this->access_only_allowed_members_or_client_contact($client_id);

        $ticket_type_id = $this->request->getPost('ticket_type_id');
        $assigned_to = $this->request->getPost('assigned_to');

        $requested_by = $this->request->getPost('requested_by_id');

        //if this logged in user is a client then overwrite the client id
        if ($this->login_user->user_type === "client") {
            $client_id = $this->login_user->client_id;
            $assigned_to = 0;
        }


        $now = get_current_utc_time();

        $ticket_data = array(
            "title" => $this->request->getPost('title'),
            "client_id" => $client_id,
            "project_id" => $this->request->getPost('project_id') ? $this->request->getPost('project_id') : 0,
            "ticket_type_id" => $ticket_type_id,
            "created_by" => $this->login_user->id,
            "created_at" => $now,
            "last_activity_at" => $now,
            "labels" => $this->request->getPost('labels'),
            "assigned_to" => $assigned_to ? $assigned_to : 0,
            "requested_by" => $requested_by ? $requested_by : 0
        );

        if (!$id) {
            $ticket_data["creator_name"] = "";
            $ticket_data["creator_email"] = "";
        }

        $ticket_data = clean_data($ticket_data);

        if ($id) {
            //client can't update ticket
            if ($this->login_user->user_type === "client") {
                app_redirect("forbidden");
            }

            //remove not updateable fields
            unset($ticket_data['client_id']);
            unset($ticket_data['created_by']);
            unset($ticket_data['created_at']);
            unset($ticket_data['last_activity_at']);
        }


        $ticket_id = $this->Tickets_model->ci_save($ticket_data, $id);

        $target_path = get_setting("timeline_file_path");
        $files_data = move_files_from_temp_dir_to_permanent_dir($target_path, "ticket");


        if ($ticket_id) {

            save_custom_fields("tickets", $ticket_id, $this->login_user->is_admin, $this->login_user->user_type);

            //ticket added. now add a comment in this ticket
            if (!$id) {
                $comment_data = array(
                    "description" => $this->request->getPost('description'),
                    "ticket_id" => $ticket_id,
                    "created_by" => $this->login_user->id,
                    "created_at" => $now
                );

                $comment_data = clean_data($comment_data);

                $comment_data["files"] = $files_data; //don't clean serilized data

                $ticket_comment_id = $this->Ticket_comments_model->ci_save($comment_data);

                if ($ticket_comment_id) {
                    log_notification("ticket_created", array("ticket_id" => $ticket_id, "ticket_comment_id" => $ticket_comment_id));
                }

                if ($this->login_user->user_type !== "staff") {
                    //don't add auto reply if it's created by team members
                    add_auto_reply_to_ticket($ticket_id);
                }
            } else if ($assigned_to) {
                log_notification("ticket_assigned", array("ticket_id" => $ticket_id, "to_user_id" => $assigned_to));
            }

            echo json_encode(array("success" => true, "data" => $this->_row_data($ticket_id), 'id' => $ticket_id, 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    /* upload a file */

    function upload_file() {
        upload_file_to_temp();
    }

    /* check valid file for ticket */

    function validate_ticket_file() {
        return validate_post_file($this->request->getPost("file_name"));
    }

    // list of tickets, prepared for datatable 
    function list_data($is_widget = 0) {
        $this->access_only_allowed_members();

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("tickets", $this->login_user->is_admin, $this->login_user->user_type);

        $status = $this->request->getPost("status");
        $ticket_label = $this->request->getPost("ticket_label");
        $assigned_to = $this->request->getPost("assigned_to");
        $ticket_type_id = $this->request->getPost('ticket_type_id');
        $options = array("status" => $status,
            "ticket_types" => $this->allowed_ticket_types,
            "ticket_label" => $ticket_label,
            "assigned_to" => $assigned_to,
            "custom_fields" => $custom_fields,
            "created_at" => $this->request->getPost('created_at'),
            "ticket_type_id" => $ticket_type_id,
            "show_assigned_tickets_only_user_id" => $this->show_assigned_tickets_only_user_id()
        );

        if ($is_widget) {
            $options = array(
                "status" => "open",
                "ticket_types" => $this->allowed_ticket_types,
                "custom_fields" => $custom_fields
            );
        }

        $list_data = $this->Tickets_model->get_details($options)->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }
        echo json_encode(array("data" => $result));
    }

    // list of tickets of a specific client, prepared for datatable 
    function ticket_list_data_of_client($client_id, $is_widget = 0) {
        $this->access_only_allowed_members_or_client_contact($client_id);

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("tickets", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array(
            "client_id" => $client_id,
            "access_type" => $this->access_type,
            "show_assigned_tickets_only_user_id" => $this->show_assigned_tickets_only_user_id(),
            "custom_fields" => $custom_fields
        );

        if ($is_widget) {
            $options = array(
                "client_id" => $client_id,
                "access_type" => $this->access_type,
                "status" => "open",
                "custom_fields" => $custom_fields
            );
        }

        $list_data = $this->Tickets_model->get_details($options)->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }
        echo json_encode(array("data" => $result));
    }

    // return a row of ticket list table 
    private function _row_data($id) {
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("tickets", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array(
            "id" => $id,
            "access_type" => $this->access_type,
            "show_assigned_tickets_only_user_id" => $this->show_assigned_tickets_only_user_id(),
            "custom_fields" => $custom_fields
        );

        $data = $this->Tickets_model->get_details($options)->getRow();
        return $this->_make_row($data, $custom_fields);
    }

    //prepare a row of ticket list table
    private function _make_row($data, $custom_fields) {
        $ticket_status_class = "bg-danger";
        if ($data->status === "new") {
            $ticket_status_class = "bg-warning";
        } else if ($data->status === "closed") {
            $ticket_status_class = "bg-success";
        } else if ($data->status === "client_replied" && $this->login_user->user_type === "client") {
            $data->status = "open"; //don't show client_replied status to client
        }

        $ticket_status = "<span class='badge $ticket_status_class large clickable'>" . app_lang($data->status) . "</span> ";


        $title = anchor(get_uri("tickets/view/" . $data->id), $data->title);


        //show labels fild to team members only
        $ticket_labels = make_labels_view_data($data->labels_list, true);
        if ($ticket_labels) {
            $title .= "<span class='float-end'>" . $ticket_labels . "</span>";
        }

        //show assign to field to team members only
        $assigned_to = "-";
        if ($data->assigned_to && $this->login_user->user_type == "staff") {
            $image_url = get_avatar($data->assigned_to_avatar);
            $assigned_to_user = "<span class='avatar avatar-xs mr10'><img src='$image_url' alt='...'></span> $data->assigned_to_user";
            $assigned_to = get_team_member_profile_link($data->assigned_to, $assigned_to_user);
        }

        $row_data = array(
            anchor(get_uri("tickets/view/" . $data->id), get_ticket_id($data->id)),
            $title,
            $data->company_name ? anchor(get_uri("clients/view/" . $data->client_id), $data->company_name) : app_lang("unknown_client"),
            $data->project_title ? anchor(get_uri("projects/view/" . $data->project_id), $data->project_title) : "-",
            $data->ticket_type ? $data->ticket_type : "-",
            $assigned_to,
            $data->last_activity_at,
            format_to_relative_time($data->last_activity_at),
            $ticket_status
        );

        foreach ($custom_fields as $field) {
            $cf_id = "cfv_" . $field->id;
            $row_data[] = $this->template->view("custom_fields/output_" . $field->field_type, array("value" => $data->$cf_id));
        }

        if ($this->login_user->user_type == "staff") {
            $options = array("id" => $data->id);

            $ticket_info = $this->Tickets_model->get_details($options)->getRow();

            if ($ticket_info) {
                $this->access_only_allowed_members_or_client_contact($ticket_info->client_id);

                $edit = '<li role="presentation">' . modal_anchor(get_uri("tickets/modal_form"), "<i data-feather='edit' class='icon-16'></i> " . app_lang('edit'), array("title" => app_lang('edit'), "data-post-view" => "details", "data-post-id" => $ticket_info->id, "class" => "dropdown-item")) . '</li>';

                //show option to close/open the tickets
                $status = "";
                if ($ticket_info->status === "closed") {
                    $status = '<li role="presentation">' . js_anchor("<i data-feather='check-circle' class='icon-16'></i> " . app_lang('mark_as_open'), array('title' => app_lang('mark_as_open'), "class" => "dropdown-item", "data-action-url" => get_uri("tickets/save_ticket_status/$ticket_info->id/open"), "data-action" => "update")) . '</li>';
                } else {
                    $status = '<li role="presentation">' . js_anchor("<i data-feather='check-circle' class='icon-16'></i> " . app_lang('mark_as_closed'), array('title' => app_lang('mark_as_closed'), "class" => "dropdown-item", "data-action-url" => get_uri("tickets/save_ticket_status/$ticket_info->id/closed"), "data-action" => "update")) . '</li>';
                }

                $assigned_to = "";
                if ($ticket_info->assigned_to === "0") {
                    $assigned_to = '<li role="presentation">' . js_anchor("<i data-feather='user' class='icon-16'></i> " . app_lang('assign_to_me'), array('title' => app_lang('assign_myself_in_this_ticket'), "data-action-url" => get_uri("tickets/assign_to_me/$ticket_info->id"), "data-action" => "update", "class" => "dropdown-item")) . '</li>';
                }


                //show the delete menu if user has access to delete the tickets
                $delete_ticket = "";
                if ($this->can_delete_tickets()) {
                    $delete_ticket = '<li role="presentation">' . js_anchor("<i data-feather='x' class='icon-16'></i>" . app_lang('delete'), array('title' => app_lang('delete'), "class" => "delete dropdown-item", "data-id" => $data->id, "data-action-url" => get_uri("tickets/delete"), "data-action" => "delete-confirmation")) . '</li>';
                }

                $row_data[] = '
                        <span class="dropdown inline-block">
                            <button class="btn btn-default dropdown-toggle caret mt0 mb0" type="button" data-bs-toggle="dropdown" aria-expanded="true" data-bs-display="static">
                                <i data-feather="tool" class="icon-16"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" role="menu">' . $edit . $status . $assigned_to . $delete_ticket . '</ul>
                        </span>';
            }
        }

        return $row_data;
    }

    // load ticket details view 
    function view($ticket_id = 0) {

        if ($ticket_id) {
            $this->_check_permission_of_selected_ticket($ticket_id);

            $sort_as_decending = get_setting("show_recent_ticket_comments_at_the_top");

            $options = array("id" => $ticket_id);
            $options["ticket_types"] = $this->allowed_ticket_types;


            $view_data["can_create_tasks"] = false;

            $ticket_info = $this->Tickets_model->get_details($options)->getRow();

            if ($ticket_info) {
                $this->access_only_allowed_members_or_client_contact($ticket_info->client_id);

                //For project related tickets, check task cration permission for the project
                if ($ticket_info->project_id) {
                    $this->init_project_permission_checker($ticket_info->project_id);
                    $view_data["can_create_tasks"] = $this->can_create_tasks();
                }

                $view_data['ticket_info'] = $ticket_info;

                $comments_options = array(
                    "ticket_id" => $ticket_id,
                    "sort_as_decending" => $sort_as_decending
                );


                $view_data['comments'] = $this->Ticket_comments_model->get_details($comments_options)->getResult();

                $view_data['custom_fields_list'] = $this->Custom_fields_model->get_combined_details("tickets", $ticket_info->id, $this->login_user->is_admin, $this->login_user->user_type)->getResult();

                $view_data["sort_as_decending"] = $sort_as_decending;

                $view_data["show_project_reference"] = get_setting('project_reference_in_tickets');

                $view_data["can_create_client"] = false;
                if ($this->login_user->is_admin || (get_array_value($this->login_user->permissions, "client") == "all")) {
                    $view_data["can_create_client"] = true;
                }

                return $this->template->rander("tickets/view", $view_data);
            } else {
                show_404();
            }
        }
    }

    //delete ticket and sub comments

    function delete() {

        if (!$this->can_delete_tickets()) {
            app_redirect("forbidden");
        }

        $id = $this->request->getPost('id');
        $this->_check_permission_of_selected_ticket($id);

        $this->validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        if ($this->Tickets_model->delete_ticket_and_sub_items($id)) {
            echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
        }
    }

    function save_comment() {
        $ticket_id = $this->request->getPost('ticket_id');
        $description = $this->request->getPost('description');
        $now = get_current_utc_time();
        $this->_check_permission_of_selected_ticket($ticket_id);

        $target_path = get_setting("timeline_file_path");
        $files_data = move_files_from_temp_dir_to_permanent_dir($target_path, "ticket");

        $comment_data = array(
            "description" => $description,
            "ticket_id" => $ticket_id,
            "created_by" => $this->login_user->id,
            "created_at" => $now,
            "files" => $files_data
        );

        $this->validate_submitted_data(array(
            "description" => "required",
            "ticket_id" => "required|numeric"
        ));

        $comment_data = clean_data($comment_data);
        $comment_data["files"] = $files_data; //don't clean serialized data

        $comment_id = $this->Ticket_comments_model->ci_save($comment_data);
        if ($comment_id) {
            //update ticket status;
            if ($this->login_user->user_type === "client") {
                $ticket_data = array(
                    "status" => "client_replied",
                    "last_activity_at" => $now
                );
            } else {
                $ticket_data = array(
                    "status" => "open",
                    "last_activity_at" => $now
                );
            }

            $ticket_data = clean_data($ticket_data);

            $this->Tickets_model->ci_save($ticket_data, $ticket_id);

            $comments_options = array("id" => $comment_id);
            $view_data['comment'] = $this->Ticket_comments_model->get_details($comments_options)->getRow();
            $comment_view = $this->template->view("tickets/comment_row", $view_data);
            echo json_encode(array("success" => true, "data" => $comment_view, 'message' => app_lang('comment_submited')));

            log_notification("ticket_commented", array("ticket_id" => $ticket_id, "ticket_comment_id" => $comment_id));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    function save_ticket_status($ticket_id = 0, $status = "closed") {
        if ($ticket_id) {
            $this->_check_permission_of_selected_ticket($ticket_id);

            $data = array(
                "status" => $status
            );

            $save_id = $this->Tickets_model->ci_save($data, $ticket_id);
            if ($save_id) {
                if ($status == "open") {
                    log_notification("ticket_reopened", array("ticket_id" => $ticket_id));
                } else if ($status == "closed") {
                    log_notification("ticket_closed", array("ticket_id" => $ticket_id));

                    //save closing time
                    $closed_data = array("closed_at" => get_current_utc_time());
                    $this->Tickets_model->ci_save($closed_data, $ticket_id);
                }

                echo json_encode(array("success" => true, "data" => $this->_row_data($ticket_id), "id" => $ticket_id, "message" => ($status == "closed") ? app_lang('ticket_closed') : app_lang('ticket_reopened')));
            } else {
                echo json_encode(array("success" => false, app_lang('error_occurred')));
            }
        }
    }

    /* download files by zip */

    function download_comment_files($id) {

        $files = $this->Ticket_comments_model->get_one($id)->files;
        return $this->download_app_files(get_setting("timeline_file_path"), $files);
    }

    //check if user has permission on this ticket
    private function _check_permission_of_selected_ticket($ticket_id = 0) {
        if ($ticket_id && $this->access_type !== "all") {
            $ticket_info = $this->Tickets_model->get_one($ticket_id);

            if ($this->access_type === "assigned_only") {
                if ($ticket_info->assigned_to !== $this->login_user->id) {
                    app_redirect("forbidden");
                }
            } else if ($this->access_type === "specific") {
                if (!in_array($ticket_info->ticket_type_id, $this->allowed_ticket_types)) {
                    app_redirect("forbidden");
                }
            } else if ($this->login_user->user_type === "client") {
                if ($ticket_info->client_id != $this->login_user->client_id) {
                    app_redirect("forbidden");
                }
            } else {
                app_redirect("forbidden");
            }
        }
    }

    function assign_to_me($ticket_id = 0) {
        if ($ticket_id) {

            $this->_check_permission_of_selected_ticket($ticket_id);

            $data = array(
                "assigned_to" => $this->login_user->id
            );

            $save_id = $this->Tickets_model->ci_save($data, $ticket_id);
            if ($save_id) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($ticket_id), "id" => $ticket_id, "message" => app_lang("record_saved")));
            } else {
                echo json_encode(array("success" => false, app_lang('error_occurred')));
            }
        }
    }

    //load the ticket templates view of ticket template list
    function ticket_templates() {
        $this->access_only_team_members();
        return $this->template->rander("tickets/templates/index");
    }

    private function can_view_ticket_template($id = 0) {
        if ($id) {
            $template_info = $this->Ticket_templates_model->get_one($id);
            if ($template_info->private && $template_info->created_by !== $this->login_user->id) {
                app_redirect("forbidden");
            }
        }
    }

    private function can_edit_ticket_template($id = 0) {
        if ($id) {
            $template_info = $this->Ticket_templates_model->get_one($id);
            //admin could modify all public templates
            //team member could modify only own templates
            if ($this->login_user->is_admin && (!$template_info->private || $template_info->created_by !== $this->login_user->id)) {
                return true;
            } else if ($template_info->private && $template_info->created_by == $this->login_user->id) {
                return true;
            } else {
                app_redirect("forbidden");
            }
        }
    }

    //add or edit form of ticket template form
    function ticket_template_modal_form() {
        $this->access_only_team_members();
        $where = array();
        if ($this->login_user->user_type === "staff" && $this->access_type !== "all" && $this->access_type !== "assigned_only") {
            $where = array("where_in" => array("id" => $this->allowed_ticket_types));
        }

        $view_data['ticket_types_dropdown'] = array("" => "-") + $this->Ticket_types_model->get_dropdown_list(array("title"), "id", $where);

        $id = $this->request->getPost('id');
        $this->can_edit_ticket_template($id);

        $view_data['model_info'] = $this->Ticket_templates_model->get_one($id);

        return $this->template->view('tickets/templates/modal_form', $view_data);
    }

    // add a new ticket template
    function save_ticket_template() {
        $this->access_only_team_members();
        $this->validate_submitted_data(array(
            "id" => "numeric",
            "title" => "required",
            "description" => "required"
        ));

        $id = $this->request->getPost('id');
        $this->can_edit_ticket_template($id);

        $private = $this->request->getPost('private');

        if (is_null($private)) {
            $private = "";
        }

        $now = get_current_utc_time();

        $ticket_template_data = array(
            "title" => $this->request->getPost('title'),
            "description" => $this->request->getPost('description'),
            "ticket_type_id" => $this->request->getPost('ticket_type_id') ? $this->request->getPost('ticket_type_id') : 0,
            "private" => $private,
            "created_by" => $this->login_user->id,
            "created_at" => $now
        );

        $save_id = $this->Ticket_templates_model->ci_save($ticket_template_data, $id);

        if ($save_id) {
            echo json_encode(array("success" => true, 'id' => $save_id, "data" => $this->_row_data_for_ticket_templates($save_id), 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    function delete_ticket_template() {
        $id = $this->request->getPost('id');
        $this->can_edit_ticket_template($id);

        $this->validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        if ($this->Ticket_templates_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
        }
    }

    function ticket_template_list_data($view_type = "", $ticket_type_id = 0) {
        $options = array("created_by" => $this->login_user->id, "ticket_type_id" => $ticket_type_id);
        if ($this->login_user->user_type === "staff" && $this->access_type !== "all" && $this->access_type !== "assigned_only") {
            $options["allowed_ticket_types"] = $this->allowed_ticket_types;
        }

        $list_data = $this->Ticket_templates_model->get_details($options)->getResult();

        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row_for_ticket_templates($data, $view_type);
        }

        echo json_encode(array("data" => $result));
    }

    // return a row of ticket template table 
    private function _row_data_for_ticket_templates($id) {
        $options = array(
            "id" => $id
        );

        $data = $this->Ticket_templates_model->get_details($options)->getRow();
        return $this->_make_row_for_ticket_templates($data);
    }

    private function _make_row_for_ticket_templates($data, $view_type = "") {

        if ($view_type == "modal") {
            $title = $data->title;
            $ticket_type = "";
        } else {
            $title = modal_anchor(get_uri("tickets/ticket_template_view/" . $data->id), $data->title, array("class" => "edit", "title" => app_lang('ticket_template'), "data-post-id" => $data->id));
            $ticket_type = $data->ticket_type ? $data->ticket_type : "-";
        }

        $private = "";
        if ($data->private) {
            $private = app_lang("yes");
        } else {
            $private = app_lang("no");
        }

        //only creator and admin can edit/delete templates
        $actions = modal_anchor(get_uri("tickets/ticket_template_view/" . $data->id), "<i data-feather='cloud-lightning' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('template_details'), "data-modal-title" => app_lang('template'), "data-post-id" => $data->id));
        if ($data->created_by == $this->login_user->id || $this->login_user->is_admin) {
            $actions = modal_anchor(get_uri("tickets/ticket_template_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_template'), "data-post-id" => $data->id))
                    . js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("tickets/delete_ticket_template"), "data-action" => "delete-confirmation"));
        }

        if ($view_type == "modal") {
            return array(
                "<div class='media-body template-row'>
                        <div class='truncate-ellipsis'>
                            <strong class='truncate-ellipsis'><span>$title</span></strong>
                        </div>
                        <div class='truncate-ellipsis js-description'><span>$data->description</span></div>
                    </div></div>"
            );
        } else {
            return array(
                "<div class='truncate-ellipsis'><span>$title</span></div>",
                "<div class='truncate-ellipsis js-description'><span>$data->description</span></div>",
                $ticket_type,
                $private,
                $actions
            );
        }
    }

    function ticket_template_view($id) {
        $this->can_view_ticket_template($id);
        $view_data['model_info'] = $this->Ticket_templates_model->get_one($id);

        return $this->template->view('tickets/templates/view', $view_data);
    }

    //show a modal to choose a template for comment a ticket
    function insert_template_modal_form() {
        $this->access_only_team_members();
        $view_data['ticket_type_id'] = $this->request->getPost('ticket_type_id');

        return $this->template->view("tickets/templates/insert_template_modal_form", $view_data);
    }

    //add client when there has unknown client
    function add_client_modal_form($ticket_id = 0) {
        if ($ticket_id) {
            $this->access_only_allowed_members();

            $view_data['clients_dropdown'] = array("" => "-") + $this->Clients_model->get_dropdown_list(array("company_name"));
            $view_data['ticket_id'] = $ticket_id;

            return $this->template->view("tickets/add_client_modal_form", $view_data);
        }
    }

    function link_to_client() {
        $this->access_only_allowed_members();

        $this->validate_submitted_data(array(
            "client_id" => "required|numeric",
            "ticket_id" => "required|numeric"
        ));

        $ticket_id = $this->request->getPost("ticket_id");
        $this->_check_permission_of_selected_ticket($ticket_id);

        $data = array("client_id" => $this->request->getPost("client_id"));
        $save_id = $this->Tickets_model->ci_save($data, $ticket_id);

        if ($save_id) {
            echo json_encode(array("success" => true, "message" => app_lang("record_saved")));
        } else {
            echo json_encode(array("success" => false, app_lang('error_occurred')));
        }
    }

    /* load tickets settings modal */

    function settings_modal_form() {
        return $this->template->view('tickets/settings/modal_form');
    }

    /* save tickets settings */

    function save_settings() {
        $settings = array("signature");

        foreach ($settings as $setting) {
            $value = $this->request->getPost($setting);
            if (is_null($value)) {
                $value = "";
            }
            $this->Settings_model->save_setting("user_" . $this->login_user->id . "_" . $setting, $value, "user");
        }

        echo json_encode(array("success" => true, 'message' => app_lang('settings_updated')));
    }

    /* prepare client contact dropdown based on this suggestion */

    function get_client_contact_suggestion($client_id = 0) {

        $this->access_only_allowed_members();

        $clients = $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", array("deleted" => 0, "client_id" => $client_id));
        $suggestion = array(array("id" => "", "text" => "-"));
        foreach ($clients as $key => $value) {
            $suggestion[] = array("id" => $key, "text" => $value);
        }
        echo json_encode($suggestion);
    }

    private function _get_ticket_types_dropdown_list_for_filter() {

        $where = array();
        if ($this->login_user->user_type === "staff" && $this->access_type !== "all" && $this->access_type !== "assigned_only") {
            $where = array("where_in" => array("id" => $this->allowed_ticket_types));
        }

        $ticket_type = $this->Ticket_types_model->get_dropdown_list(array("title"), "id", $where);

        $ticket_type_dropdown = array(array("id" => "", "text" => "- " . app_lang("ticket_type") . " -"));
        foreach ($ticket_type as $id => $name) {
            $ticket_type_dropdown[] = array("id" => $id, "text" => $name);
        }
        return $ticket_type_dropdown;
    }

}

/* End of file tickets.php */
/* Location: ./app/controllers/tickets.php */