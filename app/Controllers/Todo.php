<?php

namespace App\Controllers;

class Todo extends Security_Controller {

    function __construct() {
        parent::__construct();
    }

    protected function validate_access($todo_info) {
        if ($this->login_user->id !== $todo_info->created_by) {
            app_redirect("forbidden");
        }
    }

    //load todo list view
    function index() {
        $this->check_module_availability("module_todo");

        return $this->template->rander("todo/index");
    }

    function modal_form() {
        $view_data['model_info'] = $this->Todo_model->get_one($this->request->getPost('id'));

        //check permission for saved todo list
        if ($view_data['model_info']->id) {
            $this->validate_access($view_data['model_info']);
        }

        $view_data['label_suggestions'] = $this->make_labels_dropdown("to_do", $view_data['model_info']->labels);
        return $this->template->view('todo/modal_form', $view_data);
    }

    function save() {
        $this->validate_submitted_data(array(
            "id" => "numeric",
            "title" => "required"
        ));

        $id = $this->request->getPost('id');

        $data = array(
            "title" => $this->request->getPost('title'),
            "description" => $this->request->getPost('description') ? $this->request->getPost('description') : "",
            "created_by" => $this->login_user->id,
            "labels" => $this->request->getPost('labels') ? $this->request->getPost('labels') : "",
            "start_date" => $this->request->getPost('start_date'),
        );

        $data = clean_data($data);

        //set null value after cleaning the data
        if (!$data["start_date"]) {
            $data["start_date"] = NULL;
        }

        if ($id) {
            //saving existing todo. check permission
            $todo_info = $this->Todo_model->get_one($id);

            $this->validate_access($todo_info);
        } else {
            $data['created_at'] = get_current_utc_time();
        }

        $save_id = $this->Todo_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    /* upadate a task status */

    function save_status() {

        $this->validate_submitted_data(array(
            "id" => "numeric|required",
            "status" => "required"
        ));

        $todo_info = $this->Todo_model->get_one($this->request->getPost('id'));
        $this->validate_access($todo_info);

        $data = array(
            "status" => $this->request->getPost('status')
        );

        $save_id = $this->Todo_model->ci_save($data, $this->request->getPost('id'));

        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, "message" => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, app_lang('error_occurred')));
        }
    }

    function delete() {
        $this->validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->request->getPost('id');

        $todo_info = $this->Todo_model->get_one($id);
        $this->validate_access($todo_info);

        if ($this->request->getPost('undo')) {
            if ($this->Todo_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => app_lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, app_lang('error_occurred')));
            }
        } else {
            if ($this->Todo_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
            }
        }
    }

    function list_data() {

        $status = $this->request->getPost('status') ? implode(",", $this->request->getPost('status')) : "";
        $options = array(
            "created_by" => $this->login_user->id,
            "status" => $status
        );

        $list_data = $this->Todo_model->get_details($options)->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Todo_model->get_details($options)->getRow();
        return $this->_make_row($data);
    }

    private function _make_row($data) {
        $title = modal_anchor(get_uri("todo/view/" . $data->id), $data->title, array("class" => "edit", "title" => app_lang('todo'), "data-post-id" => $data->id));

        if ($data->labels_list) {
            $todo_labels = make_labels_view_data($data->labels_list, true);
            $title .= "<span class='float-end'>" . $todo_labels . "</span>";
        }


        $status_class = "";
        $checkbox_class = "checkbox-blank";
        if ($data->status === "to_do") {
            $status_class = "b-warning";
        } else {
            $checkbox_class = "checkbox-checked";
            $status_class = "b-success";
        }

        $check_status = js_anchor("<span class='$checkbox_class float-start'></span>", array('title' => "", "class" => "", "data-id" => $data->id, "data-value" => $data->status === "done" ? "to_do" : "done", "data-act" => "update-todo-status-checkbox"));

        $start_date_text = "";
        if (is_date_exists($data->start_date)) {
            $start_date_text = format_to_date($data->start_date, false);
            if (get_my_local_time("Y-m-d") > $data->start_date && $data->status != "done") {
                $start_date_text = "<span class='text-danger'>" . $start_date_text . "</span> ";
            } else if (get_my_local_time("Y-m-d") == $data->start_date && $data->status != "done") {
                $start_date_text = "<span class='text-warning'>" . $start_date_text . "</span> ";
            }
        }


        return array(
            $status_class,
            "<i class='hide'>" . $data->id . "</i>" . $check_status,
            $title,
            $data->start_date,
            $start_date_text,
            modal_anchor(get_uri("todo/modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit'), "data-post-id" => $data->id))
            . js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("todo/delete"), "data-action" => "delete"))
        );
    }

    function view() {
        $this->validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $model_info = $this->Todo_model->get_details(array("id" => $this->request->getPost('id')))->getRow();

        $this->validate_access($model_info);

        $view_data['model_info'] = $model_info;
        return $this->template->view('todo/view', $view_data);
    }

}

/* End of file todo.php */
/* Location: ./app/controllers/todo.php */