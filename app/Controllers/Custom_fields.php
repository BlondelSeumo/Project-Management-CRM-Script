<?php

namespace App\Controllers;

class Custom_fields extends Security_Controller {

    function __construct() {
        parent::__construct();
        $this->access_only_admin();
    }

    function index() {
        app_redirect("custom_fields/view");
    }

    function view($tab = "client") {
        $view_data["tab"] = $tab;
        return $this->template->rander("custom_fields/settings/index", $view_data);
    }

    //add/edit fields
    function modal_form() {

        $model_info = $this->Custom_fields_model->get_one($this->request->getPost('id'));
        $related_to = $model_info->related_to;
        if (!$related_to) {
            $related_to = $this->request->getPost("related_to");
        }
        $view_data['model_info'] = $model_info;
        $view_data['related_to'] = $related_to;

        return $this->template->view('custom_fields/settings/modal_form', $view_data);
    }

    //save/update custom field
    function save() {

        $id = $this->request->getPost('id');

        $validate = array(
            "id" => "numeric",
            "title" => "required",
            "related_to" => "required"
        );

        //field type is required when inserting
        if (!$id) {
            $validate["field_type"] = "required";
        }

        $this->validate_submitted_data($validate);

        $related_to = $this->request->getPost('related_to');

        $data = array(
            "title" => $this->request->getPost('title'),
            "placeholder" => $this->request->getPost('placeholder'),
            "example_variable_name" => strtoupper($this->request->getPost('example_variable_name')),
            "required" => $this->request->getPost('required') ? 1 : 0,
            "show_in_table" => $this->request->getPost('show_in_table') ? 1 : 0,
            "show_in_invoice" => $this->request->getPost('show_in_invoice') ? 1 : 0,
            "show_in_estimate" => $this->request->getPost('show_in_estimate') ? 1 : 0,
            "show_in_order" => $this->request->getPost('show_in_order') ? 1 : 0,
            "visible_to_admins_only" => $this->request->getPost('visible_to_admins_only') ? 1 : 0,
            "hide_from_clients" => $this->request->getPost('hide_from_clients') ? 1 : 0,
            "disable_editing_by_clients" => $this->request->getPost('disable_editing_by_clients') ? 1 : 0,
            "show_on_kanban_card" => $this->request->getPost('show_on_kanban_card') ? 1 : 0,
            "related_to" => $this->request->getPost('related_to'),
            "options" => $this->request->getPost('options') ? $this->request->getPost('options') : ""
        );

        if (!$id) {
            $data["field_type"] = $this->request->getPost('field_type');
        }


        if (!$id) {
            //get sort value
            $max_sort_value = $this->Custom_fields_model->get_max_sort_value($related_to);
            $data["sort"] = $max_sort_value * 1 + 1; //increase sort value
        }

        $save_id = $this->Custom_fields_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'newData' => $id ? false : true, 'id' => $save_id, 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    //prepare data for datatable for fields list
    function list_data($related_to) {
        // accessable from client and team members 

        $options = array("related_to" => $related_to);
        $list_data = $this->Custom_fields_model->get_details($options)->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_field_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    //get a row of fields list
    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Custom_fields_model->get_details($options)->getRow();
        return $this->_make_field_row($data);
    }

    //prepare a row of fields list
    private function _make_field_row($data) {

        $required = "";
        if ($data->required) {
            $required = "*";
        }

        $field = "<label for='custom_field_$data->id' data-id='$data->id' class='field-row'>$data->title $required</label>";
        $field .= "<div class='form-group'>" . $this->template->view("custom_fields/input_" . $data->field_type, array("field_info" => $data)) . "</div>";



        return array(
            $field,
            $data->sort,
            modal_anchor(get_uri("custom_fields/modal_form/"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_field'), "data-post-id" => $data->id))
            . js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete_field'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("custom_fields/delete"), "data-action" => "delete"))
        );
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
                $this->Custom_fields_model->ci_save($data, $id);
            }
        }
    }

    //delete/undo field
    function delete() {

        $this->validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->request->getPost('id');

        if ($this->request->getPost('undo')) {
            if ($this->Custom_fields_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => app_lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, app_lang('error_occurred')));
            }
        } else {
            if ($this->Custom_fields_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
            }
        }
    }

    function leads() {
        return $this->template->view('custom_fields/settings/leads');
    }

    function client_contacts() {
        return $this->template->view('custom_fields/settings/client_contacts');
    }

    function lead_contacts() {
        return $this->template->view('custom_fields/settings/lead_contacts');
    }

    function projects() {
        return $this->template->view('custom_fields/settings/projects');
    }

    function tasks() {
        return $this->template->view('custom_fields/settings/tasks');
    }

    function team_members() {
        return $this->template->view('custom_fields/settings/team_members');
    }

    function tickets() {
        return $this->template->view('custom_fields/settings/tickets');
    }

    function invoices() {
        return $this->template->view('custom_fields/settings/invoices');
    }

    function events() {
        return $this->template->view('custom_fields/settings/events');
    }

    function expenses() {
        return $this->template->view('custom_fields/settings/expenses');
    }

    function estimates() {
        return $this->template->view('custom_fields/settings/estimates');
    }

    function orders() {
        return $this->template->view('custom_fields/settings/orders');
    }

    function timesheets() {
        return $this->template->view('custom_fields/settings/timesheets');
    }

}

/* End of file custom_fields.php */
/* Location: ./app/controllers/custom_fields.php */