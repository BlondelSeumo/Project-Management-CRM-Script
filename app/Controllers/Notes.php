<?php

namespace App\Controllers;

class Notes extends Security_Controller {

    function __construct() {
        parent::__construct();
        $this->access_only_team_members();
    }

    protected function validate_access_to_note($note_info, $edit_mode = false) {
        if ($note_info->is_public) {
            //it's a public note. visible to all available users
            if ($edit_mode) {
                //for edit mode, only creator and admin can access
                if ($this->login_user->id !== $note_info->created_by && !$this->login_user->is_admin) {
                    app_redirect("forbidden");
                }
            }
        } else {
            if ($note_info->client_id) {
                //this is a client's note. check client access permission
                $access_info = $this->get_access_info("client");
                if ($access_info->access_type != "all") {
                    app_redirect("forbidden");
                }
            } else if ($note_info->user_id) {
                //this is a user's note. check user's access permission.
                app_redirect("forbidden");
            } else {
                //this is a private note. only available to creator
                if ($this->login_user->id !== $note_info->created_by) {
                    app_redirect("forbidden");
                }
            }
        }
    }

    //load note list view
    function index() {
        $this->check_module_availability("module_note");

        return $this->template->rander("notes/index");
    }

    function modal_form() {
        $view_data['model_info'] = $this->Notes_model->get_one($this->request->getPost('id'));
        $view_data['project_id'] = $this->request->getPost('project_id') ? $this->request->getPost('project_id') : $view_data['model_info']->project_id;
        $view_data['client_id'] = $this->request->getPost('client_id') ? $this->request->getPost('client_id') : $view_data['model_info']->client_id;
        $view_data['user_id'] = $this->request->getPost('user_id') ? $this->request->getPost('user_id') : $view_data['model_info']->user_id;

        //check permission for saved note
        if ($view_data['model_info']->id) {
            $this->validate_access_to_note($view_data['model_info'], true);
        }

        $view_data['label_suggestions'] = $this->make_labels_dropdown("note", $view_data['model_info']->labels, false);
        return $this->template->view('notes/modal_form', $view_data);
    }

    function save() {
        $this->validate_submitted_data(array(
            "id" => "numeric",
            "title" => "required",
            "project_id" => "numeric",
            "client_id" => "numeric",
            "user_id" => "numeric"
        ));

        $id = $this->request->getPost('id');

        $target_path = get_setting("timeline_file_path");
        $files_data = move_files_from_temp_dir_to_permanent_dir($target_path, "note");
        $new_files = unserialize($files_data);

        $data = array(
            "title" => $this->request->getPost('title'),
            "description" => $this->request->getPost('description'),
            "created_by" => $this->login_user->id,
            "labels" => $this->request->getPost('labels'),
            "project_id" => $this->request->getPost('project_id') ? $this->request->getPost('project_id') : 0,
            "client_id" => $this->request->getPost('client_id') ? $this->request->getPost('client_id') : 0,
            "user_id" => $this->request->getPost('user_id') ? $this->request->getPost('user_id') : 0,
            "is_public" => $this->request->getPost('is_public') ? $this->request->getPost('is_public') : 0
        );

        if ($id) {
            $note_info = $this->Notes_model->get_one($id);
            $timeline_file_path = get_setting("timeline_file_path");

            $new_files = update_saved_files($timeline_file_path, $note_info->files, $new_files);
        }

        $data["files"] = serialize($new_files);

        if ($id) {
            //saving existing note. check permission
            $note_info = $this->Notes_model->get_one($id);

            $this->validate_access_to_note($note_info, true);
        } else {
            $data['created_at'] = get_current_utc_time();
        }

        $data = clean_data($data);

        $save_id = $this->Notes_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    function delete() {
        $this->validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->request->getPost('id');

        $note_info = $this->Notes_model->get_one($id);
        $this->validate_access_to_note($note_info, true);

        if ($this->Notes_model->delete($id)) {
            //delete the files
            $file_path = get_setting("timeline_file_path");
            if ($note_info->files) {
                $files = unserialize($note_info->files);

                foreach ($files as $file) {
                    delete_app_files($file_path, array($file));
                }
            }

            echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
        }
    }

    function list_data($type = "", $id = 0) {
        $options = array();

        if ($type == "project" && $id) {
            $options["created_by"] = $this->login_user->id;
            $options["project_id"] = $id;
        } else if ($type == "client" && $id) {
            $options["client_id"] = $id;
        } else if ($type == "user" && $id) {
            $options["user_id"] = $id;
        } else {
            $options["created_by"] = $this->login_user->id;
            $options["my_notes"] = true;
        }

        $list_data = $this->Notes_model->get_details($options)->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Notes_model->get_details($options)->getRow();
        return $this->_make_row($data);
    }

    private function _make_row($data) {
        $public_icon = "";
        if ($data->is_public) {
            $public_icon = "<i data-feather='globe' class='icon-16'></i> ";
        }

        $title = modal_anchor(get_uri("notes/view/" . $data->id), $public_icon . $data->title, array("title" => app_lang('note'), "data-post-id" => $data->id));

        if ($data->labels_list) {
            $note_labels = make_labels_view_data($data->labels_list, true);
            $title .= "<br />" . $note_labels;
        }

        $files_link = "";
        if ($data->files) {
            $files = unserialize($data->files);
            if (count($files)) {
                foreach ($files as $key => $value) {
                    $file_name = get_array_value($value, "file_name");
                    $link = get_file_icon(strtolower(pathinfo($file_name, PATHINFO_EXTENSION)));
                    $files_link .= js_anchor("<i data-feather='$link'></i>", array('title' => "", "data-toggle" => "app-modal", "data-sidebar" => "0", "class" => "float-start font-22 mr10", "title" => remove_file_prefix($file_name), "data-url" => get_uri("notes/file_preview/" . $data->id . "/" . $key)));
                }
            }
        }

        //only creator and admin can edit/delete notes
        $actions = modal_anchor(get_uri("notes/view/" . $data->id), "<i data-feather='cloud-lightning' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('note_details'), "data-modal-title" => app_lang("note"), "data-post-id" => $data->id));
        if ($data->created_by == $this->login_user->id || $this->login_user->is_admin) {
            $actions = modal_anchor(get_uri("notes/modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_note'), "data-post-id" => $data->id))
                    . js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete_note'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("notes/delete"), "data-action" => "delete-confirmation"));
        }


        return array(
            $data->created_at,
            format_to_relative_time($data->created_at),
            $title,
            $files_link,
            $actions
        );
    }

    function view() {
        $this->validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $model_info = $this->Notes_model->get_details(array("id" => $this->request->getPost('id')))->getRow();

        $this->validate_access_to_note($model_info);

        $view_data['model_info'] = $model_info;
        return $this->template->view('notes/view', $view_data);
    }

    function file_preview($id = "", $key = "") {
        if ($id) {
            $note_info = $this->Notes_model->get_one($id);
            $files = unserialize($note_info->files);
            $file = get_array_value($files, $key);

            $file_name = get_array_value($file, "file_name");
            $file_id = get_array_value($file, "file_id");
            $service_type = get_array_value($file, "service_type");

            $view_data["file_url"] = get_source_url_of_file($file, get_setting("timeline_file_path"));
            $view_data["is_image_file"] = is_image_file($file_name);
            $view_data["is_google_preview_available"] = is_google_preview_available($file_name);
            $view_data["is_viewable_video_file"] = is_viewable_video_file($file_name);
            $view_data["is_google_drive_file"] = ($file_id && $service_type == "google") ? true : false;

            return $this->template->view("notes/file_preview", $view_data);
        } else {
            show_404();
        }
    }

    /* upload a file */

    function upload_file() {
        upload_file_to_temp();
    }

    /* check valid file for notes */

    function validate_notes_file() {
        return validate_post_file($this->request->getPost("file_name"));
    }

}

/* End of file notes.php */
/* Location: ./app/controllers/notes.php */