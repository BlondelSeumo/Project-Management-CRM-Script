<?php

namespace App\Controllers;

class Payment_methods extends Security_Controller {

    function __construct() {
        parent::__construct();
        $this->access_only_admin();
    }

    //load payment methods list
    function index() {
        return $this->template->rander("payment_methods/index");
    }

    //load payment method add/edit form
    function modal_form() {

        $this->validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Payment_methods_model->get_one_with_settings($this->request->getPost('id'));

        //get seetings associtated with this payment type
        $view_data['settings'] = $this->Payment_methods_model->get_settings($view_data['model_info']->type);

        return $this->template->view('payment_methods/modal_form', $view_data);
    }

    //save a payment method
    function save() {

        $this->validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->request->getPost('id');
        $data = array(
            "title" => $this->request->getPost('title'),
            "description" => $this->request->getPost('description'),
            "available_on_invoice" => unformat_currency($this->request->getPost('available_on_invoice')),
            "minimum_payment_amount" => unformat_currency($this->request->getPost('minimum_payment_amount'))
        );


        //get seetings associtated with this payment type
        $model_info = $this->Payment_methods_model->get_one($id);

        $settings = $this->Payment_methods_model->get_settings($model_info->type);
        $settings_data = array();
        foreach ($settings as $setting) {
            $field_type = get_array_value($setting, "type");
            $settings_name = get_array_value($setting, "name");
            $value = $this->request->getPost($settings_name);

            if ($field_type == "boolean" && $value != "1") {
                $value = "0";
            }

            if ($field_type != "readonly") {
                $settings_data[$settings_name] = $value;
            }
        }

        $data["settings"] = serialize($settings_data);


        $save_id = $this->Payment_methods_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    //delete/undo a payment method
    function delete() {

        $this->validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->request->getPost('id');
        if ($this->request->getPost('undo')) {
            if ($this->Payment_methods_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => app_lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, app_lang('error_occurred')));
            }
        } else {
            if ($this->Payment_methods_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
            }
        }
    }

    //prepare payment method list data for datatable.
    function list_data() {
        $list_data = $this->Payment_methods_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    //get a payment method list row
    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Payment_methods_model->get_details($options)->getRow();
        return $this->_make_row($data);
    }

    //prepare payment method list row
    private function _make_row($data) {
        $options = modal_anchor(get_uri("payment_methods/modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_payment_method'), "data-post-id" => $data->id));

        if (!$data->online_payable) {
            $options .= js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete_payment_method'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("payment_methods/delete"), "data-action" => "delete"));
        }

        return array($data->title,
            $data->description,
            $data->online_payable ? ($data->available_on_invoice ? app_lang("yes") : app_lang("no")) : "-",
            $data->minimum_payment_amount ? to_decimal_format($data->minimum_payment_amount) : "-",
            $options
        );
    }

}

/* End of file payment_methods.php */
/* Location: ./app/controllers/payment_methods.php */