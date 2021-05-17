<?php

namespace App\Controllers;

class Lead_source extends Security_Controller {

    function __construct() {
        parent::__construct();
        $this->access_only_admin();
    }

    function index() {
        return $this->template->view("lead_source/index");
    }

    function modal_form() {
        $this->validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Lead_source_model->get_one($this->request->getPost('id'));
        return $this->template->view('lead_source/modal_form', $view_data);
    }

    function save() {
        $this->validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->request->getPost('id');
        $data["title"] = $this->request->getPost('title');

        if (!$id) {
            //get sort value
            $max_sort_value = $this->Lead_source_model->get_max_sort_value();
            $data["sort"] = $max_sort_value * 1 + 1; //increase sort value
        }

        $save_id = $this->Lead_source_model->ci_save($data, $id);

        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    //update the sort value for the fields
    function update_field_sort_values($id = 0) {

        $sort_values = $this->request->getPost("sort_values");
        if ($sort_values) {

            //extract the values from the comma separated string
            $sort_array = explode(",", $sort_values);


            //update the value in db
            foreach ($sort_array as $value) {
                $sort_item = explode("-", $value); //extract id and sort value

                $id = get_array_value($sort_item, 0);
                $sort = get_array_value($sort_item, 1);

                $data = array("sort" => $sort);
                $this->Lead_source_model->ci_save($data, $id);
            }
        }
    }

    function delete() {
        $this->validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->request->getPost('id');

        if ($this->request->getPost('undo')) {
            if ($this->Lead_source_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => app_lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, app_lang('error_occurred')));
            }
        } else {
            if ($this->Lead_source_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
            }
        }
    }

    function list_data() {
        $list_data = $this->Lead_source_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Lead_source_model->get_details($options)->getRow();
        return $this->_make_row($data);
    }

    private function _make_row($data) {
        $edit = modal_anchor(get_uri("lead_source/modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_lead_source'), "data-post-id" => $data->id));

        $delete = js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete_lead_source'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("lead_source/delete"), "data-action" => "delete-confirmation"));

        return array(
            $data->sort,
            "<div class='field-row'  data-id='$data->id'><div class='float-start move-icon'><i data-feather='menu' class='icon-16'></i></div>" . $data->title . '</div>',
            $edit . $delete
        );
    }

}

/* End of file Lead_source.php */
/* Location: ./app/controllers/Lead_source.php */