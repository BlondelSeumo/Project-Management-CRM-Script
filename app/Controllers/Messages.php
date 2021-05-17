<?php

namespace App\Controllers;

class Messages extends Security_Controller {

    function __construct() {
        parent::__construct();
        $this->init_permission_checker("message_permission");
    }

    private function is_my_message($message_info) {
        if ($message_info->from_user_id == $this->login_user->id || $message_info->to_user_id == $this->login_user->id) {
            return true;
        }
    }

    private function check_message_user_permission() {
        if (!$this->check_access_on_messages_for_this_user()) {
            app_redirect("forbidden");
        }
    }

    private function check_validate_sending_message($to_user_id) {
        if (!$this->validate_sending_message($to_user_id)) {
            echo json_encode(array("success" => false, 'message' => app_lang("message_sending_error_message")));
            exit;
        }
    }

    function index() {
        $this->check_message_user_permission();
        app_redirect("messages/inbox");
    }

    /* show new message modal */

    function modal_form($user_id = 0) {
        $this->check_message_user_permission();
        $view_data['users_dropdown'] = array("" => "-");

        if ($user_id) {
            $view_data['message_user_info'] = $this->Users_model->get_one($user_id);
        } else {
            $users = $this->Messages_model->get_users_for_messaging($this->get_user_options_for_query())->getResult();

            foreach ($users as $user) {
                $user_name = $user->first_name . " " . $user->last_name;

                if ($user->user_type === "client" && $user->company_name) { //user is a client contact
                    if ($this->login_user->user_type == "staff") {
                        $user_name .= " - " . app_lang("client") . ": " . $user->company_name . "";
                    } else {
                        $user_name = app_lang("contact") . ": " . $user_name;
                    }
                }

                $view_data['users_dropdown'][$user->id] = $user_name;
            }
        }

        return $this->template->view('messages/modal_form', $view_data);
    }

    /* show inbox */

    function inbox($auto_select_index = "") {
        $this->check_message_user_permission();
        $this->check_module_availability("module_message");

        $view_data['mode'] = "inbox";
        $view_data['auto_select_index'] = $auto_select_index;
        return $this->template->rander("messages/index", $view_data);
    }

    /* show sent items */

    function sent_items($auto_select_index = "") {
        $this->check_message_user_permission();
        $this->check_module_availability("module_message");

        $view_data['mode'] = "sent_items";
        $view_data['auto_select_index'] = $auto_select_index;
        return $this->template->rander("messages/index", $view_data);
    }

    private function get_allowed_user_ids() {
        $users = $this->Messages_model->get_users_for_messaging($this->get_user_options_for_query())->getResult();
        $users = json_decode(json_encode($users), true); //convert to array
        return implode(',', array_column($users, "id"));
    }

    /* list of messages, prepared for datatable  */

    function list_data($mode = "inbox") {
        $this->check_message_user_permission();
        if ($mode !== "inbox") {
            $mode = "sent_items";
        }

        $options = array("user_id" => $this->login_user->id, "mode" => $mode, "user_ids" => $this->get_allowed_user_ids());
        $list_data = $this->Messages_model->get_list($options)->getResult();

        $result = array();

        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $mode);
        }

        echo json_encode(array("data" => $result));
    }

    /* return a message details */

    function view($message_id = 0, $mode = "", $reply = 0) {
        $this->check_message_user_permission();

        $message_mode = $mode;
        if ($reply == 1 && $mode == "inbox") {
            $message_mode = "sent_items";
        } else if ($reply == 1 && $mode == "sent_items") {
            $message_mode = "inbox";
        }

        $options = array("id" => $message_id, "user_id" => $this->login_user->id, "mode" => $message_mode);
        $view_data["message_info"] = $this->Messages_model->get_details($options)->row;

        if (!$this->is_my_message($view_data["message_info"])) {
            app_redirect("forbidden");
        }

        //change message status to read
        $this->Messages_model->set_message_status_as_read($view_data["message_info"]->id, $this->login_user->id);

        $replies_options = array("message_id" => $message_id, "user_id" => $this->login_user->id, "limit" => 4);
        $messages = $this->Messages_model->get_details($replies_options);

        $view_data["replies"] = $messages->result;
        $view_data["found_rows"] = $messages->found_rows;

        $view_data["mode"] = $mode;
        $view_data["is_reply"] = $reply;
        echo json_encode(array("success" => true, "data" => $this->template->view("messages/view", $view_data), "message_id" => $message_id));
    }

    /* prepare a row of message list table */

    private function _make_row($data, $mode = "", $return_only_message = false, $online_status = false) {
        $image_url = get_avatar($data->user_image);
        $created_at = format_to_relative_time($data->created_at);
        $message_id = $data->main_message_id;
        $label = "";
        $reply = "";
        $status = "";
        $attachment_icon = "";
        $subject = $data->subject;
        if ($mode == "inbox") {
            $status = $data->status;
        }

        if ($data->reply_subject) {
            $label = " <label class='badge bg-success d-inline-block'>" . app_lang('reply') . "</label>";
            $reply = "1";
            $subject = $data->reply_subject;
        }

        if ($data->files && count(unserialize($data->files))) {
            $attachment_icon = "<i data-feather='paperclip' class='icon-14 mr15'></i>";
        }


        //prepare online status
        $online = "";
        if ($online_status && is_online_user($data->last_online)) {
            $online = "<i class='online'></i>";
        }

        $message = "<div class='message-row $status' data-id='$message_id' data-index='$data->main_message_id' data-reply='$reply'><div class='d-flex'><div class='flex-shrink-0'>
                        <span class='avatar avatar-xs'>
                            <img src='$image_url' />
                                $online
                        </span>
                    </div>
                    <div class='w-100 ps-3'>
                        <div class='mb5'>
                            <strong> $data->user_name</strong>
                                  <span class='text-off float-end time'>$attachment_icon $created_at</span>
                        </div>
                        $label $subject
                    </div></div></div>
                  
                ";
        if ($return_only_message) {
            return $message;
        } else {
            return array(
                $message,
                $data->created_at,
                $status
            );
        }
    }

    /* send new message */

    function send_message() {
        $this->check_message_user_permission();

        $this->validate_submitted_data(array(
            "message" => "required",
            "to_user_id" => "required|numeric"
        ));

        $to_user_id = $this->request->getPost('to_user_id');

        //team member can send message to any team member
        //client can send messages to only allowed members

        $this->check_validate_sending_message($to_user_id);

        $target_path = get_setting("timeline_file_path");
        $files_data = move_files_from_temp_dir_to_permanent_dir($target_path, "message");


        $message_data = array(
            "from_user_id" => $this->login_user->id,
            "to_user_id" => $to_user_id,
            "subject" => $this->request->getPost('subject'),
            "message" => $this->request->getPost('message'),
            "created_at" => get_current_utc_time(),
            "deleted_by_users" => "",
        );

        $message_data = clean_data($message_data);

        $message_data["files"] = $files_data; //don't clean serilized data

        $save_id = $this->Messages_model->ci_save($message_data);

        if ($save_id) {
            log_notification("new_message_sent", array("actual_message_id" => $save_id));
            echo json_encode(array("success" => true, 'message' => app_lang('message_sent'), "id" => $save_id));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    /* reply to an existing message */

    function reply($is_chat = 0) {
        $this->check_message_user_permission();
        $message_id = $this->request->getPost('message_id');

        $this->validate_submitted_data(array(
            "reply_message" => "required",
            "message_id" => "required|numeric"
        ));


        $message_info = $this->Messages_model->get_one($message_id);

        if (!$this->is_my_message($message_info)) {
            app_redirect("forbidden");
        }


        if ($message_info->id) {
            //check, where we have to send this message
            $to_user_id = 0;
            if ($message_info->from_user_id === $this->login_user->id) {
                $to_user_id = $message_info->to_user_id;
            } else {
                $to_user_id = $message_info->from_user_id;
            }

            $this->check_validate_sending_message($to_user_id);

            $target_path = get_setting("timeline_file_path");
            $files_data = move_files_from_temp_dir_to_permanent_dir($target_path, "message");

            $message = $this->request->getPost('reply_message');

            $message_data = array(
                "from_user_id" => $this->login_user->id,
                "to_user_id" => $to_user_id,
                "message_id" => $message_id,
                "subject" => "",
                "message" => $message,
                "created_at" => get_current_utc_time(),
                "deleted_by_users" => "",
            );

            $message_data = clean_data($message_data);
            $message_data["files"] = $files_data; //don't clean serilized data


            $save_id = $this->Messages_model->ci_save($message_data);

            if ($save_id) {

                //if chat via pusher is enabled, then send message data to pusher
                if (get_setting('enable_chat_via_pusher') && get_setting("enable_push_notification")) {
                    send_message_via_pusher($to_user_id, $message_data, $message_id);
                }

                //we'll not send notification, if the user is online

                if ($this->request->getPost("is_user_online") !== "1") {
                    log_notification("message_reply_sent", array("actual_message_id" => $save_id, "parent_message_id" => $message_id));
                }

                //clear the delete status, if the mail deleted
                $this->Messages_model->clear_deleted_status($message_id);

                if ($is_chat) {
                    echo json_encode(array("success" => true, 'data' => $this->_load_messages($message_id, $this->request->getPost("last_message_id"), 0, $to_user_id)));
                } else {
                    $options = array("id" => $save_id, "user_id" => $this->login_user->id);
                    $view_data['reply_info'] = $this->Messages_model->get_details($options)->row;
                    echo json_encode(array("success" => true, 'message' => app_lang('message_sent'), 'data' => $this->template->view("messages/reply_row", $view_data)));
                }

                return;
            }
        }
        echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
    }

    //load messages right panel when clicking load more button
    function view_messages() {

        $this->check_message_user_permission();
        $this->validate_submitted_data(array(
            "message_id" => "required|numeric",
            "last_message_id" => "numeric",
            "top_message_id" => "numeric"
        ));

        $message_id = $this->request->getPost("message_id");

        echo $this->_load_more_messages($message_id, $this->request->getPost("last_message_id"), $this->request->getPost("top_message_id"));
    }

    //prepare the chat box messages 
    private function _load_more_messages($message_id, $last_message_id, $top_message_id) {

        $replies_options = array("message_id" => $message_id, "last_message_id" => $last_message_id, "top_message_id" => $top_message_id, "user_id" => $this->login_user->id, "limit" => 10);

        $view_data["replies"] = $this->Messages_model->get_details($replies_options)->result;
        $view_data["message_id"] = $message_id;

        $this->Messages_model->set_message_status_as_read($message_id, $this->login_user->id);

        return $this->template->view("messages/reply_rows", $view_data);
    }

    /* prepare notifications */

    function get_notifications() {
        $this->validate_submitted_data(array(
            "active_message_id" => "numeric"
        ));


        $notifiations = $this->Messages_model->get_notifications($this->login_user->id, $this->login_user->message_checked_at, $this->request->getPost("active_message_id"));
        $view_data['notifications'] = $notifiations->getResult();
        echo json_encode(array("success" => true, "active_message_id" => $this->request->getPost("active_message_id"), 'total_notifications' => $notifiations->resultID->num_rows, 'notification_list' => $this->template->view("messages/notifications", $view_data)));
    }

    function update_notification_checking_status() {
        $now = get_current_utc_time();
        $user_data = array("message_checked_at" => $now);
        $this->Users_model->ci_save($user_data, $this->login_user->id);
    }

    /* upload a file */

    function upload_file() {
        upload_file_to_temp();
    }

    /* check valid file for message */

    function validate_message_file() {
        return validate_post_file($this->request->getPost("file_name"));
    }

    /* download files by zip */

    function download_message_files($message_id = "") {
        $model_info = $this->Messages_model->get_one($message_id);
        if (!$this->is_my_message($model_info)) {
            app_redirect("forbidden");
        }

        $files = $model_info->files;


        $timeline_file_path = get_setting("timeline_file_path");
        return $this->download_app_files($timeline_file_path, $files);
    }

    function delete_my_messages($id = 0) {

        if (!$id) {
            exit();
        }

        //delete messages for current user.
        $this->Messages_model->delete_messages_for_user($id, $this->login_user->id);
    }

    //prepare chat inbox list
    function chat_list() {
        $this->check_message_user_permission();

        $view_data['show_users_list'] = false;
        $view_data['show_clients_list'] = false;

        $client_message_users = get_setting("client_message_users");
        if ($this->login_user->user_type === "staff") {
            //user is team member
            $client_message_users_array = explode(",", $client_message_users);
            if (in_array($this->login_user->id, $client_message_users_array)) {
                //user can send message to clients
                $view_data['show_clients_list'] = true;
            }

            if (get_array_value($this->login_user->permissions, "message_permission") !== "no") {
                //user can send message to team members
                $view_data['show_users_list'] = true;
            }
        } else {
            //user is a client contact and can send messages
            if ($client_message_users) {
                $view_data['show_users_list'] = true;
            }

            //user can send message to own client contacts
            if (get_setting("client_message_own_contacts")) {
                $view_data['show_clients_list'] = true;
            }
        }

        $options = array("login_user_id" => $this->login_user->id, "user_ids" => $this->get_allowed_user_ids());

        $view_data['messages'] = $this->Messages_model->get_chat_list($options)->getResult();

        return $this->template->view("messages/chat/tabs", $view_data);
    }

    function users_list($type) {
        $view_data["users"] = $this->Messages_model->get_users_for_messaging($this->get_user_options_for_query($type))->getResult();

        $page_type = "";
        if ($type === "staff") {
            $page_type = "team-members-tab";
        } else {
            $page_type = "clients-tab";
        }

        $view_data["page_type"] = $page_type;

        return $this->template->view("messages/chat/team_members", $view_data);
    }

    //load messages in chat view
    function view_chat() {

        $this->check_message_user_permission();

        $this->validate_submitted_data(array(
            "message_id" => "required|numeric",
            "last_message_id" => "numeric",
            "top_message_id" => "numeric",
            "another_user_id" => "numeric"
        ));

        $message_id = $this->request->getPost("message_id");

        $another_user_id = $this->request->getPost("another_user_id");

        if ($this->request->getPost("is_first_load") == "1") {
            $view_data["first_message"] = $this->Messages_model->get_details(array("id" => $message_id, "user_id" => $this->login_user->id))->row;
            echo $this->template->view("messages/chat/message_title", $view_data);
        }

        echo $this->_load_messages($message_id, $this->request->getPost("last_message_id"), $this->request->getPost("top_message_id"), $another_user_id);
    }

    //prepare the chat box messages 
    private function _load_messages($message_id, $last_message_id, $top_message_id, $another_user_id = "") {

        $replies_options = array("message_id" => $message_id, "last_message_id" => $last_message_id, "top_message_id" => $top_message_id, "user_id" => $this->login_user->id);

        $view_data["replies"] = $this->Messages_model->get_details($replies_options)->result;
        $view_data["message_id"] = $message_id;

        $this->Messages_model->set_message_status_as_read($message_id, $this->login_user->id);

        $is_online = false;
        if ($another_user_id) {
            $last_online = $this->Users_model->get_one($another_user_id)->last_online;
            if ($last_online) {
                $is_online = is_online_user($last_online);
            }
        }

        $view_data['is_online'] = $is_online;

        return $this->template->view("messages/chat/message_items", $view_data);
    }

    function get_active_chat() {

        $this->validate_submitted_data(array(
            "message_id" => "required|numeric"
        ));

        $message_id = $this->request->getPost("message_id");

        $options = array("id" => $message_id, "user_id" => $this->login_user->id);
        $view_data["message_info"] = $this->Messages_model->get_details($options)->row;

        if (!$this->is_my_message($view_data["message_info"])) {
            app_redirect("forbidden");
        }

        //$this->Messages_model->set_message_status_as_read($view_data["message_info"]->id, $this->login_user->id);

        $view_data["message_id"] = $message_id;
        return $this->template->view("messages/chat/active_chat", $view_data);
    }

    function get_chatlist_of_user() {

        $this->check_message_user_permission();

        $this->validate_submitted_data(array(
            "user_id" => "required|numeric"
        ));

        $user_id = $this->request->getPost("user_id");

        $options = array("user_id" => $user_id, "login_user_id" => $this->login_user->id);
        $view_data["messages"] = $this->Messages_model->get_chat_list($options)->getResult();


        $user_info = $this->Users_model->get_one_where(array("id" => $user_id, "status" => "active", "deleted" => "0"));
        $view_data["user_name"] = $user_info->first_name . " " . $user_info->last_name;

        $view_data["user_id"] = $user_id;
        $view_data["tab_type"] = $this->request->getPost("tab_type");

        return $this->template->view("messages/chat/get_chatlist_of_user", $view_data);
    }

    function send_typing_indicator_to_pusher() {
        $message_id = $this->request->getPost("message_id");
        if (!$message_id) {
            show_404();
        }

        $message_info = $this->Messages_model->get_one($message_id);
        if (!$this->is_my_message($message_info)) {
            app_redirect("forbidden");
        }

        if ($message_info->id) {
            //check, where we have to send this message
            $to_user_id = 0;
            if ($message_info->from_user_id === $this->login_user->id) {
                $to_user_id = $message_info->to_user_id;
            } else {
                $to_user_id = $message_info->from_user_id;
            }

            $this->check_validate_sending_message($to_user_id);

            if (get_setting('enable_chat_via_pusher') && get_setting("enable_push_notification")) {
                send_message_via_pusher($to_user_id, "", $message_id, "typing");
            }
        } else {
            show_404();
        }
    }

}

/* End of file messages.php */
    /* Location: ./app/controllers/messages.php */    