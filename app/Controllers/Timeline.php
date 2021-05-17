<?php

namespace App\Controllers;

class Timeline extends Security_Controller {

    public function __construct() {
        parent::__construct();
        $this->access_only_team_members();
    }

    /* load timeline view */

    function index() {
        $this->check_module_availability("module_timeline");

        $view_data['team_members'] = "";
        $this->init_permission_checker("message_permission");
        if (get_array_value($this->login_user->permissions, "message_permission") !== "no") {
            $view_data['team_members'] = $this->Messages_model->get_users_for_messaging($this->get_user_options_for_query("staff"))->getResult();
        }

        return $this->template->rander("timeline/index", $view_data);
    }

    /* save a post */

    function save() {
        $this->validate_submitted_data(array(
            "description" => "required"
        ));

        $id = $this->request->getPost('id');

        $post_id = $this->request->getPost('post_id');

        $target_path = get_setting("timeline_file_path");

        $files_data = move_files_from_temp_dir_to_permanent_dir($target_path, "timeline_post");

        $data = array(
            "created_by" => $this->login_user->id,
            "created_at" => get_current_utc_time(),
            "post_id" => $post_id,
            "description" => $this->request->getPost('description'),
            "share_with" => ""
        );

        $data = clean_data($data);
        $data["files"] = $files_data; //don't clean serilized data

        $save_id = $this->Posts_model->ci_save($data, $id);
        if ($save_id) {
            $data = "";
            if ($this->request->getPost("reload_list")) {
                $options = array("id" => $save_id);
                $view_data['posts'] = $this->Posts_model->get_details($options)->result;
                $view_data['result_remaining'] = 0;
                $view_data['is_first_load'] = false;
                $view_data['single_post'] = '';
                $data = $this->template->view("timeline/post_list", $view_data);
            }
            echo json_encode(array("success" => true, "data" => $data, 'message' => app_lang('comment_submited')));

            if ($post_id == 0) {
                log_notification("created_a_new_post", array("post_id" => $save_id));
            } else {
                log_notification("timeline_post_commented", array("post_id" => $save_id));
            }
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    function delete($id = 0) {

        if (!$id) {
            exit();
        }

        $post_info = $this->Posts_model->get_one($id);

        //only admin and creator can delete the post
        if (!($this->login_user->is_admin || $post_info->created_by == $this->login_user->id)) {
            app_redirect("forbidden");
        }


        //delete the post and files
        if ($this->Posts_model->delete($id) && $post_info->files) {

            //delete the files
            $timeline_file_path = get_setting("timeline_file_path");
            $files = unserialize($post_info->files);

            delete_app_files($timeline_file_path, $files);
        }
    }

    /* load all replies of a post */

    function view_post_replies($post_id) {
        $view_data['reply_list'] = $this->Posts_model->get_details(array("post_id" => $post_id))->result;
        return $this->template->view("timeline/reply_list", $view_data);
    }

    /* show post reply form */

    function post_reply_form($post_id) {
        $view_data['post_id'] = $post_id;
        return $this->template->view("timeline/reply_form", $view_data);
    }

    /* upload a post file */

    function upload_file() {
        upload_file_to_temp();
    }

    /* check valid file for post */

    function validate_post_file() {
        return validate_post_file($this->request->getPost("file_name"));
    }

    function download_files($id) {
        $files = $this->Posts_model->get_one($id)->files;
        return $this->download_app_files(get_setting("timeline_file_path"), $files);
    }

    /* load more posts */

    function load_more_posts($offset = 0) {
        return timeline_widget(array("limit" => 20, "offset" => $offset));
    }

    /* post page for notification */

    function post($post_id) {
        //check if it's a post's comment
        $original_post_info = $this->Posts_model->get_one($post_id);
        if ($original_post_info->post_id) {
            $post_id = $original_post_info->post_id;
        }

        $post = $this->Posts_model->get_details(array("id" => $post_id));
        $view_data["posts"] = $post->result;
        $view_data['is_first_load'] = true;
        $view_data['result_remaining'] = 0;
        $view_data['single_post'] = 'single_post';
        return $this->template->rander("timeline/post_list", $view_data);
    }

}

/* End of file timeline.php */
    /* Location: ./app/controllers/timeline.php */