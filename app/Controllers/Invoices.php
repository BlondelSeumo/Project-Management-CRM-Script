<?php

namespace App\Controllers;

use App\Libraries\Paypal;
use App\Libraries\Paytm;

class Invoices extends Security_Controller {

    function __construct() {
        parent::__construct();
        $this->init_permission_checker("invoice");
    }

    /* load invoice list view */

    function index() {
        $this->check_module_availability("module_invoice");

        $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("invoices", $this->login_user->is_admin, $this->login_user->user_type);

        $view_data["can_edit_invoices"] = $this->can_edit_invoices();

        if ($this->login_user->user_type === "staff") {
            if (!$this->can_view_invoices()) {
                app_redirect("forbidden");
            }

            $view_data["currencies_dropdown"] = $this->_get_currencies_dropdown();

            return $this->template->rander("invoices/index", $view_data);
        } else {
            $view_data["client_info"] = $this->Clients_model->get_one($this->login_user->client_id);
            $view_data['client_id'] = $this->login_user->client_id;
            $view_data['page_type'] = "full";
            return $this->template->rander("clients/invoices/index", $view_data);
        }
    }

    //load the yearly view of invoice list 
    function yearly() {
        return $this->template->view("invoices/yearly_invoices");
    }

    //load the recurring view of invoice list 
    function recurring() {
        $view_data["currencies_dropdown"] = $this->_get_currencies_dropdown();
        $view_data["can_edit_invoices"] = $this->can_edit_invoices();
        return $this->template->view("invoices/recurring_invoices_list", $view_data);
    }

    //load the custom view of invoice list 
    function custom() {
        return $this->template->view("invoices/custom_invoices_list");
    }

    /* load new invoice modal */

    function modal_form() {
        if (!$this->can_edit_invoices()) {
            app_redirect("forbidden");
        }

        $this->validate_submitted_data(array(
            "id" => "numeric",
            "client_id" => "numeric",
            "project_id" => "numeric"
        ));

        $client_id = $this->request->getPost('client_id');
        $project_id = $this->request->getPost('project_id');
        $model_info = $this->Invoices_model->get_one($this->request->getPost('id'));


        //check if estimate_id/order_id posted. if found, generate related information
        $estimate_id = $this->request->getPost('estimate_id');
        $order_id = $this->request->getPost('order_id');
        $view_data['estimate_id'] = $estimate_id;
        $view_data['order_id'] = $order_id;

        if ($estimate_id || $order_id) {
            $info = null;
            if ($estimate_id) {
                $info = $this->Estimates_model->get_one($estimate_id);
            } else if ($order_id) {
                $info = $this->Orders_model->get_one($order_id);
            }

            if ($info) {
                $now = get_my_local_time("Y-m-d");
                $model_info->bill_date = $now;
                $model_info->due_date = $now;
                $model_info->client_id = $info->client_id;
                $model_info->tax_id = $info->tax_id;
                $model_info->tax_id2 = $info->tax_id2;
                $model_info->discount_amount = $info->discount_amount;
                $model_info->discount_amount_type = $info->discount_amount_type;
                $model_info->discount_type = $info->discount_type;
            }
        }

        //here has a project id. now set the client from the project
        if ($project_id) {
            $client_id = $this->Projects_model->get_one($project_id)->client_id;
            $model_info->client_id = $client_id;
        }


        $project_client_id = $client_id;
        if ($model_info->client_id) {
            $project_client_id = $model_info->client_id;
        }

        $view_data['model_info'] = $model_info;

        //make the drodown lists
        $view_data['taxes_dropdown'] = array("" => "-") + $this->Taxes_model->get_dropdown_list(array("title"));
        $view_data['clients_dropdown'] = array("" => "-") + $this->Clients_model->get_dropdown_list(array("company_name"), "id", array("is_lead" => 0));
        $projects = $this->Projects_model->get_dropdown_list(array("title"), "id", array("client_id" => $project_client_id));
        $suggestion = array(array("id" => "", "text" => "-"));
        foreach ($projects as $key => $value) {
            $suggestion[] = array("id" => $key, "text" => $value);
        }
        $view_data['projects_suggestion'] = $suggestion;

        $view_data['client_id'] = $client_id;
        $view_data['project_id'] = $project_id;


        //prepare label suggestions
        $view_data['label_suggestions'] = $this->make_labels_dropdown("invoice", $model_info->labels);

        //clone invoice
        $is_clone = $this->request->getPost('is_clone');
        $view_data['is_clone'] = $is_clone;


        $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("invoices", $model_info->id, $this->login_user->is_admin, $this->login_user->user_type)->getResult();


        return $this->template->view('invoices/modal_form', $view_data);
    }

    /* prepare project dropdown based on this suggestion */

    function get_project_suggestion($client_id = 0) {
        if (!$this->can_edit_invoices()) {
            app_redirect("forbidden");
        }

        $projects = $this->Projects_model->get_dropdown_list(array("title"), "id", array("client_id" => $client_id));
        $suggestion = array(array("id" => "", "text" => "-"));
        foreach ($projects as $key => $value) {
            $suggestion[] = array("id" => $key, "text" => $value);
        }
        echo json_encode($suggestion);
    }

    /* add or edit an invoice */

    function save() {
        if (!$this->can_edit_invoices()) {
            app_redirect("forbidden");
        }

        $this->validate_submitted_data(array(
            "id" => "numeric",
            "invoice_client_id" => "required|numeric",
            "invoice_bill_date" => "required",
            "invoice_due_date" => "required"
        ));

        $client_id = $this->request->getPost('invoice_client_id');
        $id = $this->request->getPost('id');

        $target_path = get_setting("timeline_file_path");
        $files_data = move_files_from_temp_dir_to_permanent_dir($target_path, "invoice");
        $new_files = unserialize($files_data);

        $recurring = $this->request->getPost('recurring') ? 1 : 0;
        $bill_date = $this->request->getPost('invoice_bill_date');
        $repeat_every = $this->request->getPost('repeat_every');
        $repeat_type = $this->request->getPost('repeat_type');
        $no_of_cycles = $this->request->getPost('no_of_cycles');


        $invoice_data = array(
            "client_id" => $client_id,
            "project_id" => $this->request->getPost('invoice_project_id') ? $this->request->getPost('invoice_project_id') : 0,
            "bill_date" => $bill_date,
            "due_date" => $this->request->getPost('invoice_due_date'),
            "tax_id" => $this->request->getPost('tax_id') ? $this->request->getPost('tax_id') : 0,
            "tax_id2" => $this->request->getPost('tax_id2') ? $this->request->getPost('tax_id2') : 0,
            "tax_id3" => $this->request->getPost('tax_id3') ? $this->request->getPost('tax_id3') : 0,
            "recurring" => $recurring,
            "repeat_every" => $repeat_every ? $repeat_every : 0,
            "repeat_type" => $repeat_type ? $repeat_type : NULL,
            "no_of_cycles" => $no_of_cycles ? $no_of_cycles : 0,
            "note" => $this->request->getPost('invoice_note'),
            "labels" => $this->request->getPost('labels')
        );

        if ($id) {
            $invoice_info = $this->Invoices_model->get_one($id);
            $timeline_file_path = get_setting("timeline_file_path");

            $new_files = update_saved_files($timeline_file_path, $invoice_info->files, $new_files);
        }

        $invoice_data["files"] = serialize($new_files);

        $is_clone = $this->request->getPost('is_clone');
        $estimate_id = $this->request->getPost('estimate_id');

        $main_invoice_id = "";
        if (($is_clone && $id) || $estimate_id) {
            if ($is_clone && $id) {
                $main_invoice_id = $id; //store main invoice id to get items later
                $id = ""; //one cloning invoice, save as new
            }

            // save discount when cloning and creating from estimate
            $invoice_data["discount_amount"] = $this->request->getPost('discount_amount') ? $this->request->getPost('discount_amount') : 0;
            $invoice_data["discount_amount_type"] = $this->request->getPost('discount_amount_type') ? $this->request->getPost('discount_amount_type') : "percentage";
            $invoice_data["discount_type"] = $this->request->getPost('discount_type') ? $this->request->getPost('discount_type') : "before_tax";
        }


        if ($recurring) {
            //set next recurring date for recurring invoices

            if ($id) {
                //update
                if ($this->request->getPost('next_recurring_date')) { //submitted any recurring date? set it.
                    $invoice_data['next_recurring_date'] = $this->request->getPost('next_recurring_date');
                } else {
                    //re-calculate the next recurring date, if any recurring fields has changed.
                    $invoice_info = $this->Invoices_model->get_one($id);
                    if ($invoice_info->recurring != $invoice_data['recurring'] || $invoice_info->repeat_every != $invoice_data['repeat_every'] || $invoice_info->repeat_type != $invoice_data['repeat_type'] || $invoice_info->bill_date != $invoice_data['bill_date']) {
                        $invoice_data['next_recurring_date'] = add_period_to_date($bill_date, $repeat_every, $repeat_type);
                    }
                }
            } else {
                //insert new
                $invoice_data['next_recurring_date'] = add_period_to_date($bill_date, $repeat_every, $repeat_type);
            }


            //recurring date must have to set a future date
            if (get_array_value($invoice_data, "next_recurring_date") && get_today_date() >= $invoice_data['next_recurring_date']) {
                echo json_encode(array("success" => false, 'message' => app_lang('past_recurring_date_error_message_title'), 'next_recurring_date_error' => app_lang('past_recurring_date_error_message'), "next_recurring_date_value" => $invoice_data['next_recurring_date']));
                return false;
            }
        }


        $invoice_id = $this->Invoices_model->ci_save($invoice_data, $id);
        if ($invoice_id) {

            if ($is_clone && $main_invoice_id) {
                //add invoice items

                save_custom_fields("invoices", $invoice_id, 1, "staff"); //we have to keep this regarding as an admin user because non-admin user also can acquire the access to clone a invoice

                $invoice_items = $this->Invoice_items_model->get_all_where(array("invoice_id" => $main_invoice_id, "deleted" => 0))->getResult();

                foreach ($invoice_items as $invoice_item) {
                    //prepare new invoice item data
                    $invoice_item_data = (array) $invoice_item;
                    unset($invoice_item_data["id"]);
                    $invoice_item_data['invoice_id'] = $invoice_id;

                    $invoice_item = $this->Invoice_items_model->ci_save($invoice_item_data);
                }
            } else {
                save_custom_fields("invoices", $invoice_id, $this->login_user->is_admin, $this->login_user->user_type);
            }

            //submitted copy_items_from_estimate/copy_items_from_order? copy all items from the associated one
            $copy_items_from_estimate = $this->request->getPost("copy_items_from_estimate");
            $copy_items_from_order = $this->request->getPost("copy_items_from_order");
            $this->_copy_estimate_or_order_items_to_invoice($copy_items_from_estimate, $copy_items_from_order, $invoice_id);

            echo json_encode(array("success" => true, "data" => $this->_row_data($invoice_id), 'id' => $invoice_id, 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    private function _copy_estimate_or_order_items_to_invoice($copy_items_from_estimate, $copy_items_from_order, $invoice_id) {
        if (!$copy_items_from_estimate && !$copy_items_from_order) {
            return false;
        }

        $items = null;
        if ($copy_items_from_estimate) {
            $items = $this->Estimate_items_model->get_details(array("estimate_id" => $copy_items_from_estimate))->getResult();
        } else if ($copy_items_from_order) {
            $items = $this->Order_items_model->get_details(array("order_id" => $copy_items_from_order))->getResult();
        }

        if (!$items) {
            return false;
        }

        foreach ($items as $data) {
            $invoice_item_data = array(
                "invoice_id" => $invoice_id,
                "title" => $data->title ? $data->title : "",
                "description" => $data->description ? $data->description : "",
                "quantity" => $data->quantity ? $data->quantity : 0,
                "unit_type" => $data->unit_type ? $data->unit_type : "",
                "rate" => $data->rate ? $data->rate : 0,
                "total" => $data->total ? $data->total : 0,
            );
            $this->Invoice_items_model->ci_save($invoice_item_data);
        }
    }

    /* delete or undo an invoice */

    function delete() {
        if (!$this->can_edit_invoices()) {
            app_redirect("forbidden");
        }

        $this->validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->request->getPost('id');

        $invoice_info = $this->Invoices_model->get_one($id);

        if ($this->Invoices_model->delete($id)) {
            //delete the files
            $file_path = get_setting("timeline_file_path");
            if ($invoice_info->files) {
                $files = unserialize($invoice_info->files);

                foreach ($files as $file) {
                    delete_app_files($file_path, array($file));
                }
            }

            echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
        }
    }

    /* list of invoices, prepared for datatable  */

    function list_data() {
        if (!$this->can_view_invoices()) {
            app_redirect("forbidden");
        }

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("invoices", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array(
            "status" => $this->request->getPost("status"),
            "start_date" => $this->request->getPost("start_date"),
            "end_date" => $this->request->getPost("end_date"),
            "currency" => $this->request->getPost("currency"),
            "custom_fields" => $custom_fields
        );

        $list_data = $this->Invoices_model->get_details($options)->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }

        echo json_encode(array("data" => $result));
    }

    /* list of invoice of a specific client, prepared for datatable  */

    function invoice_list_data_of_client($client_id) {
        if (!$this->can_view_invoices($client_id)) {
            app_redirect("forbidden");
        }

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("invoices", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array(
            "client_id" => $client_id,
            "status" => $this->request->getPost("status"),
            "custom_fields" => $custom_fields
        );

        //don't show draft invoices to client
        if ($this->login_user->user_type == "client") {
            $options["exclude_draft"] = true;
        }


        $list_data = $this->Invoices_model->get_details($options)->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }
        echo json_encode(array("data" => $result));
    }

    /* list of invoice of a specific project, prepared for datatable  */

    function invoice_list_data_of_project($project_id) {
        if (!$this->can_view_invoices()) {
            app_redirect("forbidden");
        }

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("invoices", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array(
            "project_id" => $project_id,
            "status" => $this->request->getPost("status"),
            "custom_fields" => $custom_fields
        );
        $list_data = $this->Invoices_model->get_details($options)->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }
        echo json_encode(array("data" => $result));
    }

    /* show sub invoices tab  */

    function sub_invoices($recurring_invoice_id) {
        if (!$this->can_view_invoices()) {
            app_redirect("forbidden");
        }
        $view_data["recurring_invoice_id"] = $recurring_invoice_id;
        return $this->template->view("invoices/sub_invoices", $view_data);
    }

    /* list of sub invoices of a recurring invoice, prepared for datatable  */

    function sub_invoices_list_data($recurring_invoice_id) {
        if (!$this->can_view_invoices()) {
            app_redirect("forbidden");
        }

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("invoices", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array(
            "status" => $this->request->getPost("status"),
            "start_date" => $this->request->getPost("start_date"),
            "end_date" => $this->request->getPost("end_date"),
            "custom_fields" => $custom_fields,
            "recurring_invoice_id" => $recurring_invoice_id
        );

        $list_data = $this->Invoices_model->get_details($options)->getResult();

        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $custom_fields);
        }

        echo json_encode(array("data" => $result));
    }

    /* return a row of invoice list table */

    private function _row_data($id) {
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("invoices", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array("id" => $id, "custom_fields" => $custom_fields);
        $data = $this->Invoices_model->get_details($options)->getRow();
        return $this->_make_row($data, $custom_fields);
    }

    /* prepare a row of invoice list table */

    private function _make_row($data, $custom_fields) {
        $invoice_url = "";
        if ($this->login_user->user_type == "staff") {
            $invoice_url = anchor(get_uri("invoices/view/" . $data->id), get_invoice_id($data->id));
        } else {
            $invoice_url = anchor(get_uri("invoices/preview/" . $data->id), get_invoice_id($data->id));
        }

        $invoice_labels = make_labels_view_data($data->labels_list, true, true);

        $row_data = array($invoice_url,
            anchor(get_uri("clients/view/" . $data->client_id), $data->company_name),
            $data->project_title ? anchor(get_uri("projects/view/" . $data->project_id), $data->project_title) : "-",
            $data->bill_date,
            format_to_date($data->bill_date, false),
            $data->due_date,
            format_to_date($data->due_date, false),
            to_currency($data->invoice_value, $data->currency_symbol),
            to_currency($data->payment_received, $data->currency_symbol),
            $this->_get_invoice_status_label($data) . $invoice_labels
        );

        foreach ($custom_fields as $field) {
            $cf_id = "cfv_" . $field->id;
            $row_data[] = $this->template->view("custom_fields/output_" . $field->field_type, array("value" => $data->$cf_id));
        }

        $row_data[] = $this->_make_options_dropdown($data->id);

        return $row_data;
    }

    //prepare options dropdown for invoices list
    private function _make_options_dropdown($invoice_id = 0) {
        $edit = '<li role="presentation">' . modal_anchor(get_uri("invoices/modal_form"), "<i data-feather='edit' class='icon-16'></i> " . app_lang('edit'), array("title" => app_lang('edit_invoice'), "data-post-id" => $invoice_id, "class" => "dropdown-item")) . '</li>';

        $delete = '<li role="presentation">' . js_anchor("<i data-feather='x' class='icon-16'></i>" . app_lang('delete'), array('title' => app_lang('delete_invoice'), "class" => "delete dropdown-item", "data-id" => $invoice_id, "data-action-url" => get_uri("invoices/delete"), "data-action" => "delete-confirmation")) . '</li>';

        $add_payment = '<li role="presentation">' . modal_anchor(get_uri("invoice_payments/payment_modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_payment'), array("title" => app_lang('add_payment'), "data-post-invoice_id" => $invoice_id, "class" => "dropdown-item")) . '</li>';

        return '
                <span class="dropdown inline-block">
                    <button class="btn btn-default dropdown-toggle caret mt0 mb0" type="button" data-bs-toggle="dropdown" aria-expanded="true" data-bs-display="static">
                        <i data-feather="tool" class="icon-16"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" role="menu">' . $edit . $delete . $add_payment . '</ul>
                </span>';
    }

    //prepare invoice status label 
    private function _get_invoice_status_label($data, $return_html = true) {
        return get_invoice_status_label($data, $return_html);
    }

    // list of recurring invoices, prepared for datatable
    function recurring_list_data() {
        if (!$this->can_view_invoices()) {
            app_redirect("forbidden");
        }


        $options = array(
            "recurring" => 1,
            "next_recurring_start_date" => $this->request->getPost("next_recurring_start_date"),
            "next_recurring_end_date" => $this->request->getPost("next_recurring_end_date"),
            "currency" => $this->request->getPost("currency")
        );

        $list_data = $this->Invoices_model->get_details($options)->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_recurring_row($data);
        }

        echo json_encode(array("data" => $result));
    }

    /* prepare a row of recurring invoice list table */

    private function _make_recurring_row($data) {

        $invoice_url = anchor(get_uri("invoices/view/" . $data->id), get_invoice_id($data->id));

        $cycles = $data->no_of_cycles_completed . "/" . $data->no_of_cycles;
        if (!$data->no_of_cycles) { //if not no of cycles, so it's infinity
            $cycles = $data->no_of_cycles_completed . "/&#8734;";
        }

        $status = "active";
        $invoice_status_class = "bg-success";
        $cycle_class = "";

        //don't show next recurring if recurring is completed
        $next_recurring = format_to_date($data->next_recurring_date, false);

        //show red color if any recurring date is past
        if ($data->next_recurring_date < get_today_date()) {
            $next_recurring = "<span class='text-danger'>" . $next_recurring . "</span>";
        }


        $next_recurring_date = $data->next_recurring_date;
        if ($data->no_of_cycles_completed > 0 && $data->no_of_cycles_completed == $data->no_of_cycles) {
            $next_recurring = "-";
            $next_recurring_date = 0;
            $status = "stopped";
            $invoice_status_class = "bg-danger";
            $cycle_class = "text-danger";
        }

        return array(
            $invoice_url,
            anchor(get_uri("clients/view/" . $data->client_id), $data->company_name),
            $data->project_title ? anchor(get_uri("projects/view/" . $data->project_id), $data->project_title) : "-",
            $next_recurring_date,
            $next_recurring,
            $data->repeat_every . " " . app_lang("interval_" . $data->repeat_type),
            "<span class='$cycle_class'>" . $cycles . "</span>",
            "<span class='badge $invoice_status_class large'>" . app_lang($status) . "</span>",
            to_currency($data->invoice_value, $data->currency_symbol),
            $this->_make_options_dropdown($data->id)
        );
    }

    /* load invoice details view */

    function view($invoice_id = 0) {
        if (!$this->can_view_invoices()) {
            app_redirect("forbidden");
        }

        if ($invoice_id) {
            $view_data = get_invoice_making_data($invoice_id);

            if ($view_data) {
                $view_data['invoice_status'] = $this->_get_invoice_status_label($view_data["invoice_info"], false);
                $view_data["can_edit_invoices"] = $this->can_edit_invoices();

                return $this->template->rander("invoices/view", $view_data);
            } else {
                show_404();
            }
        }
    }

    /* invoice total section */

    private function _get_invoice_total_view($invoice_id = 0) {
        $view_data["invoice_total_summary"] = $this->Invoices_model->get_invoice_total_summary($invoice_id);
        $view_data["invoice_id"] = $invoice_id;
        $view_data["can_edit_invoices"] = $this->can_edit_invoices();
        return $this->template->view('invoices/invoice_total_section', $view_data);
    }

    /* load item modal */

    function item_modal_form() {
        if (!$this->can_edit_invoices()) {
            app_redirect("forbidden");
        }

        $this->validate_submitted_data(array(
            "id" => "numeric"
        ));

        $invoice_id = $this->request->getPost('invoice_id');

        $view_data['model_info'] = $this->Invoice_items_model->get_one($this->request->getPost('id'));
        if (!$invoice_id) {
            $invoice_id = $view_data['model_info']->invoice_id;
        }
        $view_data['invoice_id'] = $invoice_id;
        return $this->template->view('invoices/item_modal_form', $view_data);
    }

    /* add or edit an invoice item */

    function save_item() {
        if (!$this->can_edit_invoices()) {
            app_redirect("forbidden");
        }

        $this->validate_submitted_data(array(
            "id" => "numeric",
            "invoice_id" => "required|numeric"
        ));

        $invoice_id = $this->request->getPost('invoice_id');

        $id = $this->request->getPost('id');
        $rate = unformat_currency($this->request->getPost('invoice_item_rate'));
        $quantity = unformat_currency($this->request->getPost('invoice_item_quantity'));

        $invoice_item_data = array(
            "invoice_id" => $invoice_id,
            "title" => $this->request->getPost('invoice_item_title'),
            "description" => $this->request->getPost('invoice_item_description'),
            "quantity" => $quantity,
            "unit_type" => $this->request->getPost('invoice_unit_type'),
            "rate" => unformat_currency($this->request->getPost('invoice_item_rate')),
            "total" => $rate * $quantity,
        );

        $invoice_item_id = $this->Invoice_items_model->ci_save($invoice_item_data, $id);
        if ($invoice_item_id) {

            //check if the add_new_item flag is on, if so, add the item to libary. 
            $add_new_item_to_library = $this->request->getPost('add_new_item_to_library');
            if ($add_new_item_to_library) {
                $library_item_data = array(
                    "title" => $this->request->getPost('invoice_item_title'),
                    "description" => $this->request->getPost('invoice_item_description'),
                    "unit_type" => $this->request->getPost('invoice_unit_type'),
                    "rate" => unformat_currency($this->request->getPost('invoice_item_rate'))
                );
                $this->Items_model->ci_save($library_item_data);
            }

            $options = array("id" => $invoice_item_id);
            $item_info = $this->Invoice_items_model->get_details($options)->getRow();
            echo json_encode(array("success" => true, "invoice_id" => $item_info->invoice_id, "data" => $this->_make_item_row($item_info), "invoice_total_view" => $this->_get_invoice_total_view($item_info->invoice_id), 'id' => $invoice_item_id, 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    /* delete or undo an invoice item */

    function delete_item() {
        if (!$this->can_edit_invoices()) {
            app_redirect("forbidden");
        }

        $this->validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->request->getPost('id');
        if ($this->request->getPost('undo')) {
            if ($this->Invoice_items_model->delete($id, true)) {
                $options = array("id" => $id);
                $item_info = $this->Invoice_items_model->get_details($options)->getRow();
                echo json_encode(array("success" => true, "invoice_id" => $item_info->invoice_id, "data" => $this->_make_item_row($item_info), "invoice_total_view" => $this->_get_invoice_total_view($item_info->invoice_id), "message" => app_lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, app_lang('error_occurred')));
            }
        } else {
            if ($this->Invoice_items_model->delete($id)) {
                $item_info = $this->Invoice_items_model->get_one($id);
                echo json_encode(array("success" => true, "invoice_id" => $item_info->invoice_id, "invoice_total_view" => $this->_get_invoice_total_view($item_info->invoice_id), 'message' => app_lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of invoice items, prepared for datatable  */

    function item_list_data($invoice_id = 0) {
        if (!$this->can_view_invoices()) {
            app_redirect("forbidden");
        }

        $list_data = $this->Invoice_items_model->get_details(array("invoice_id" => $invoice_id))->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_item_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    /* prepare a row of invoice item list table */

    private function _make_item_row($data) {
        $move_icon = "";
        $desc_style = "";
        if ($this->can_edit_invoices()) {
            $move_icon = "<div class='float-start move-icon'><i data-feather='menu' class='icon-16'></i></div>";
            $desc_style = "style='margin-left:25px'";
        }
        $item = "<div class='item-row strong mb5' data-id='$data->id'>$move_icon $data->title</div>";
        if ($data->description) {
            $item .= "<span $desc_style>" . nl2br($data->description) . "</span>";
        }
        $type = $data->unit_type ? $data->unit_type : "";

        return array(
            $data->sort,
            $item,
            to_decimal_format($data->quantity) . " " . $type,
            to_currency($data->rate, $data->currency_symbol),
            to_currency($data->total, $data->currency_symbol),
            modal_anchor(get_uri("invoices/item_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_invoice'), "data-post-id" => $data->id))
            . js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("invoices/delete_item"), "data-action" => "delete"))
        );
    }

    //update the sort value for the item
    function update_item_sort_values($id = 0) {
        if (!$this->can_edit_invoices()) {
            app_redirect("forbidden");
        }

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
                $this->Invoice_items_model->ci_save($data, $id);
            }
        }
    }

    /* prepare suggestion of invoice item */

    function get_invoice_item_suggestion() {
        $key = $_REQUEST["q"];
        $suggestion = array();

        $items = $this->Invoice_items_model->get_item_suggestion($key);

        foreach ($items as $item) {
            $suggestion[] = array("id" => $item->title, "text" => $item->title);
        }

        $suggestion[] = array("id" => "+", "text" => "+ " . app_lang("create_new_item"));

        echo json_encode($suggestion);
    }

    function get_invoice_item_info_suggestion() {
        $item = $this->Invoice_items_model->get_item_info_suggestion($this->request->getPost("item_name"));
        if ($item) {
            echo json_encode(array("success" => true, "item_info" => $item));
        } else {
            echo json_encode(array("success" => false));
        }
    }

    //view html is accessable to client only.
    function preview($invoice_id = 0, $show_close_preview = false) {
        if ($invoice_id) {
            $view_data = get_invoice_making_data($invoice_id);

            $this->_check_invoice_access_permission($view_data);

            $view_data['invoice_preview'] = prepare_invoice_pdf($view_data, "html");

            //show a back button
            $view_data['show_close_preview'] = $show_close_preview && $this->login_user->user_type === "staff" ? true : false;

            $view_data['invoice_id'] = $invoice_id;
            $view_data['payment_methods'] = $this->Payment_methods_model->get_available_online_payment_methods();

            $paypal = new Paypal();
            $view_data['paypal_url'] = $paypal->get_paypal_url();

            $paytm = new Paytm();
            $view_data['paytm_url'] = $paytm->get_paytm_url();

            return $this->template->rander("invoices/invoice_preview", $view_data);
        } else {
            show_404();
        }
    }

    //print invoice
    function print_invoice($invoice_id = 0) {
        if ($invoice_id) {
            $view_data = get_invoice_making_data($invoice_id);

            $this->_check_invoice_access_permission($view_data);

            $view_data['invoice_preview'] = prepare_invoice_pdf($view_data, "html");

            echo json_encode(array("success" => true, "print_view" => $this->template->view("invoices/print_invoice", $view_data)));
        } else {
            echo json_encode(array("success" => false, app_lang('error_occurred')));
        }
    }

    function download_pdf($invoice_id = 0, $mode = "download") {
        if ($invoice_id) {
            $invoice_data = get_invoice_making_data($invoice_id);
            $this->_check_invoice_access_permission($invoice_data);

            prepare_invoice_pdf($invoice_data, $mode);
        } else {
            show_404();
        }
    }

    private function _check_invoice_access_permission($invoice_data) {
        //check for valid invoice
        if (!$invoice_data) {
            show_404();
        }

        //check for security
        $invoice_info = get_array_value($invoice_data, "invoice_info");
        if ($this->login_user->user_type == "client") {
            if ($this->login_user->client_id != $invoice_info->client_id) {
                app_redirect("forbidden");
            }
        } else {
            if (!$this->can_view_invoices()) {
                app_redirect("forbidden");
            }
        }
    }

    function send_invoice_modal_form($invoice_id) {
        if (!$this->can_edit_invoices()) {
            app_redirect("forbidden");
        }

        if ($invoice_id) {
            $options = array("id" => $invoice_id);
            $invoice_info = $this->Invoices_model->get_details($options)->getRow();
            $view_data['invoice_info'] = $invoice_info;

            $contacts_options = array("user_type" => "client", "client_id" => $invoice_info->client_id);
            $contacts = $this->Users_model->get_details($contacts_options)->getResult();

            $primary_contact_info = "";
            $contacts_dropdown = array();
            foreach ($contacts as $contact) {
                if ($contact->is_primary_contact) {
                    $primary_contact_info = $contact;
                    $contacts_dropdown[$contact->id] = $contact->first_name . " " . $contact->last_name . " (" . app_lang("primary_contact") . ")";
                }
            }

            $cc_contacts_dropdown = array();

            foreach ($contacts as $contact) {
                if (!$contact->is_primary_contact) {
                    $contacts_dropdown[$contact->id] = $contact->first_name . " " . $contact->last_name;
                }

                $cc_contacts_dropdown[] = array("id" => $contact->id, "text" => $contact->first_name . " " . $contact->last_name);
            }

            $view_data['contacts_dropdown'] = $contacts_dropdown;
            $view_data['cc_contacts_dropdown'] = $cc_contacts_dropdown;

            $template_data = $this->get_send_invoice_template($invoice_id, 0, "", $invoice_info, $primary_contact_info);
            $view_data['message'] = get_array_value($template_data, "message");
            $view_data['subject'] = get_array_value($template_data, "subject");

            return $this->template->view('invoices/send_invoice_modal_form', $view_data);
        } else {
            show_404();
        }
    }

    function get_send_invoice_template($invoice_id = 0, $contact_id = 0, $return_type = "", $invoice_info = "", $contact_info = "") {
        if (!$this->can_edit_invoices()) {
            app_redirect("forbidden");
        }

        if (!$invoice_info) {
            $options = array("id" => $invoice_id);
            $invoice_info = $this->Invoices_model->get_details($options)->getRow();
        }

        if (!$contact_info) {
            $contact_info = $this->Users_model->get_one($contact_id);
        }

        $email_template = $this->Email_templates_model->get_final_template("send_invoice");

        $invoice_total_summary = $this->Invoices_model->get_invoice_total_summary($invoice_id);

        $parser_data["INVOICE_ID"] = $invoice_info->id;
        $parser_data["CONTACT_FIRST_NAME"] = $contact_info->first_name;
        $parser_data["CONTACT_LAST_NAME"] = $contact_info->last_name;
        $parser_data["BALANCE_DUE"] = to_currency($invoice_total_summary->balance_due, $invoice_total_summary->currency_symbol);
        $parser_data["DUE_DATE"] = format_to_date($invoice_info->due_date, false);
        $parser_data["PROJECT_TITLE"] = $invoice_info->project_title;
        $parser_data["INVOICE_URL"] = get_uri("invoices/preview/" . $invoice_info->id);
        $parser_data['SIGNATURE'] = $email_template->signature;
        $parser_data["LOGO_URL"] = get_logo_url();

        //add public pay invoice url 
        if (get_setting("client_can_pay_invoice_without_login") && strpos($email_template->message, "PUBLIC_PAY_INVOICE_URL")) {
            $verification_data = array(
                "type" => "invoice_payment",
                "code" => make_random_string(),
                "params" => serialize(array(
                    "invoice_id" => $invoice_id,
                    "client_id" => $contact_info->client_id,
                    "contact_id" => $contact_info->id
                ))
            );

            $save_id = $this->Verification_model->ci_save($verification_data);

            $verification_info = $this->Verification_model->get_one($save_id);

            $parser_data["PUBLIC_PAY_INVOICE_URL"] = get_uri("pay_invoice/index/" . $verification_info->code);
        }

        $message = $this->parser->setData($parser_data)->renderString($email_template->message);
        $message = htmlspecialchars_decode($message);
        $subject = $email_template->subject;

        if ($return_type == "json") {
            echo json_encode(array("success" => true, "message_view" => $message));
        } else {
            return array(
                "message" => $message,
                "subject" => $subject
            );
        }
    }

    function send_invoice() {
        if (!$this->can_edit_invoices()) {
            app_redirect("forbidden");
        }

        $this->validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $invoice_id = $this->request->getPost('id');

        $contact_id = $this->request->getPost('contact_id');

        $cc_array = array();
        $cc = $this->request->getPost('invoice_cc');

        if ($cc) {
            $cc = explode(',', $cc);

            foreach ($cc as $cc_value) {
                if (is_numeric($cc_value)) {
                    //selected a client contact
                    array_push($cc_array, $this->Users_model->get_one($cc_value)->email);
                } else {
                    //inputted an email address
                    array_push($cc_array, $cc_value);
                }
            }
        }

        $custom_bcc = $this->request->getPost('invoice_bcc');
        $subject = $this->request->getPost('subject');
        $message = decode_ajax_post_data($this->request->getPost('message'));

        $contact = $this->Users_model->get_one($contact_id);

        $invoice_data = get_invoice_making_data($invoice_id);
        $attachement_url = prepare_invoice_pdf($invoice_data, "send_email");

        $default_bcc = get_setting('send_bcc_to'); //get default settings
        $bcc_emails = "";

        if ($default_bcc && $custom_bcc) {
            $bcc_emails = $default_bcc . "," . $custom_bcc;
        } else if ($default_bcc) {
            $bcc_emails = $default_bcc;
        } else if ($custom_bcc) {
            $bcc_emails = $custom_bcc;
        }

        //add uploaded files
        $target_path = get_setting("timeline_file_path");
        $files_data = move_files_from_temp_dir_to_permanent_dir($target_path, "invoice");
        $attachments = prepare_attachment_of_files(get_setting("timeline_file_path"), $files_data);

        //add invoice pdf
        array_unshift($attachments, array("file_path" => $attachement_url));

        if (send_app_mail($contact->email, $subject, $message, array("attachments" => $attachments, "cc" => $cc_array, "bcc" => $bcc_emails))) {
            // change email status
            $status_data = array("status" => "not_paid", "last_email_sent_date" => get_my_local_time());
            if ($this->Invoices_model->ci_save($status_data, $invoice_id)) {
                echo json_encode(array('success' => true, 'message' => app_lang("invoice_sent_message"), "invoice_id" => $invoice_id));
            }

            // delete the temp invoice
            if (file_exists($attachement_url)) {
                unlink($attachement_url);
            }

            //delete attachments
            if ($files_data) {
                $files = unserialize($files_data);
                foreach ($files as $file) {
                    delete_app_files($target_path, array($file));
                }
            }
        } else {
            echo json_encode(array('success' => false, 'message' => app_lang('error_occurred')));
        }
    }

    function get_invoice_status_bar($invoice_id = 0) {
        if (!$this->can_view_invoices()) {
            app_redirect("forbidden");
        }

        $view_data["invoice_info"] = $this->Invoices_model->get_details(array("id" => $invoice_id))->getRow();
        $view_data['invoice_status_label'] = $this->_get_invoice_status_label($view_data["invoice_info"]);
        return $this->template->view('invoices/invoice_status_bar', $view_data);
    }

    function update_invoice_status($invoice_id = 0, $status = "") {
        if (!$this->can_edit_invoices()) {
            app_redirect("forbidden");
        }

        if ($invoice_id && $status) {
            //change the draft status of the invoice
            $this->Invoices_model->update_invoice_status($invoice_id, $status);

            //save extra information for cancellation
            if ($status == "cancelled") {
                $data = array(
                    "cancelled_at" => get_current_utc_time(),
                    "cancelled_by" => $this->login_user->id
                );

                $this->Invoices_model->ci_save($data, $invoice_id);
            }

            echo json_encode(array("success" => true, 'message' => app_lang('record_saved')));
        }

        return "";
    }

    /* load discount modal */

    function discount_modal_form() {
        if (!$this->can_edit_invoices()) {
            app_redirect("forbidden");
        }

        $this->validate_submitted_data(array(
            "invoice_id" => "required|numeric"
        ));

        $invoice_id = $this->request->getPost('invoice_id');

        $view_data['model_info'] = $this->Invoices_model->get_one($invoice_id);

        return $this->template->view('invoices/discount_modal_form', $view_data);
    }

    /* save discount */

    function save_discount() {
        if (!$this->can_edit_invoices()) {
            app_redirect("forbidden");
        }

        $this->validate_submitted_data(array(
            "invoice_id" => "required|numeric",
            "discount_type" => "required",
            "discount_amount" => "numeric",
            "discount_amount_type" => "required"
        ));

        $invoice_id = $this->request->getPost('invoice_id');

        $data = array(
            "discount_type" => $this->request->getPost('discount_type'),
            "discount_amount" => $this->request->getPost('discount_amount'),
            "discount_amount_type" => $this->request->getPost('discount_amount_type')
        );

        $data = clean_data($data);

        $save_data = $this->Invoices_model->ci_save($data, $invoice_id);
        if ($save_data) {
            echo json_encode(array("success" => true, "invoice_total_view" => $this->_get_invoice_total_view($invoice_id), 'message' => app_lang('record_saved'), "invoice_id" => $invoice_id));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    function load_statistics_of_selected_currency($currency = "") {
        if ($currency) {
            $statistics = invoice_statistics_widget(array("currency" => $currency));

            if ($statistics) {
                echo json_encode(array("success" => true, "statistics" => $statistics));
            } else {
                echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
            }
        }
    }

    /* upload a file */

    function upload_file() {
        upload_file_to_temp();
    }

    /* check valid file for invoices */

    function validate_invoices_file() {
        return validate_post_file($this->request->getPost("file_name"));
    }

    function file_preview($id = "", $key = "") {
        if ($id) {
            $invoice_info = $this->Invoices_model->get_one($id);
            $files = unserialize($invoice_info->files);
            $file = get_array_value($files, $key);

            $file_name = get_array_value($file, "file_name");
            $file_id = get_array_value($file, "file_id");
            $service_type = get_array_value($file, "service_type");

            $view_data["file_url"] = get_source_url_of_file($file, get_setting("timeline_file_path"));
            $view_data["is_image_file"] = is_image_file($file_name);
            $view_data["is_google_preview_available"] = is_google_preview_available($file_name);
            $view_data["is_viewable_video_file"] = is_viewable_video_file($file_name);
            $view_data["is_google_drive_file"] = ($file_id && $service_type == "google") ? true : false;

            return $this->template->view("invoices/file_preview", $view_data);
        } else {
            show_404();
        }
    }

}

/* End of file invoices.php */
/* Location: ./app/controllers/invoices.php */