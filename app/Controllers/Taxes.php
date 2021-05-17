<?php

namespace App\Controllers;

class Taxes extends Security_Controller {

    function __construct() {
        parent::__construct();
        $this->access_only_admin();
    }

    function index() {
        return $this->template->rander("taxes/index");
    }

    function modal_form() {

        $this->validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Taxes_model->get_one($this->request->getPost('id'));
        return $this->template->view('taxes/modal_form', $view_data);
    }

    function save() {

        $this->validate_submitted_data(array(
            "id" => "numeric",
            "title" => "required",
            "percentage" => "required"
        ));

        $id = $this->request->getPost('id');
        $data = array(
            "title" => $this->request->getPost('title'),
            "percentage" => unformat_currency($this->request->getPost('percentage'))
        );
        $save_id = $this->Taxes_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    function delete() {
        $this->validate_submitted_data(array(
            "id" => "numeric|required"
        ));


        $id = $this->request->getPost('id');
        if ($this->request->getPost('undo')) {
            if ($this->Taxes_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => app_lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, app_lang('error_occurred')));
            }
        } else {
            if ($this->Taxes_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
            }
        }
    }

    function list_data() {
        $list_data = $this->Taxes_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Taxes_model->get_details($options)->getRow();
        return $this->_make_row($data);
    }

    private function _make_row($data) {
        return array($data->title,
            to_decimal_format($data->percentage),
            modal_anchor(get_uri("taxes/modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_tax'), "data-post-id" => $data->id))
            . js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete_tax'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("taxes/delete"), "data-action" => "delete"))
        );
    }

}

/* End of file taxes.php */
/* Location: ./app/controllers/taxes.php */