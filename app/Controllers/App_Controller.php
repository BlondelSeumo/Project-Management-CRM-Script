<?php

/*
 * This controller load all the related things to run this app.
 * Extend this controller to load prerequisites only.
 */

namespace App\Controllers;

use App\Libraries\Template;
use App\Libraries\Google;
use CodeIgniter\Controller;

class App_Controller extends Controller {

    protected $template;
    public $session;
    public $form_validation;
    public $parser;

    public function __construct() {
        //main template to make frame of this app
        $this->template = new Template();

        //load helpers
        helper(array('url', 'file', 'form', 'language', 'general', 'date_time', 'app_files', 'widget', 'activity_logs', 'currency'));

        //models
        $models_array = $this->get_models_array();
        foreach ($models_array as $model) {
            $this->$model = model("App\Models\\" . $model);
        }

        $login_user_id = $this->Users_model->login_user_id();

        //assign settings from database
        $settings = $this->Settings_model->get_all_required_settings($login_user_id)->getResult();
        foreach ($settings as $setting) {
            config('Rise')->app_settings_array[$setting->setting_name] = $setting->setting_value;
        }

        //assign language
        $language = get_setting('user_' . $login_user_id . '_personal_language') ? get_setting('user_' . $login_user_id . '_personal_language') : get_setting("language");
        service('request')->setLocale($language);

        $this->session = \Config\Services::session();
        $this->form_validation = \Config\Services::validation();
        $this->parser = \Config\Services::parser();
    }

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger) {
        parent::initController($request, $response, $logger); //don't edit this line
    }

    private function get_models_array() {
        return array(
            'Settings_model',
            'Users_model',
            'Team_model',
            'Attendance_model',
            'Leave_types_model',
            'Leave_applications_model',
            'Events_model',
            'Announcements_model',
            'Messages_model',
            'Clients_model',
            'Projects_model',
            'Milestones_model',
            'Task_status_model',
            'Tasks_model',
            'Project_comments_model',
            'Activity_logs_model',
            'Project_files_model',
            'Notes_model',
            'Project_members_model',
            'Ticket_types_model',
            'Tickets_model',
            'Ticket_comments_model',
            'Items_model',
            'Invoices_model',
            'Invoice_items_model',
            'Invoice_payments_model',
            'Payment_methods_model',
            'Email_templates_model',
            'Roles_model',
            'Posts_model',
            'Timesheets_model',
            'Expenses_model',
            'Expense_categories_model',
            'Taxes_model',
            'Social_links_model',
            'Notification_settings_model',
            'Notifications_model',
            'Custom_fields_model',
            'Estimate_forms_model',
            'Estimate_requests_model',
            'Custom_field_values_model',
            'Estimates_model',
            'Estimate_items_model',
            'General_files_model',
            'Todo_model',
            'Client_groups_model',
            'Dashboards_model',
            'Lead_status_model',
            'Lead_source_model',
            'Order_items_model',
            'Orders_model',
            'Order_status_model',
            'Labels_model',
            'Verification_model',
            'Item_categories_model'
        );
    }

    //validate submitted data
    protected function validate_submitted_data($fields = array(), $return_errors = false) {
        $final_fields = array();

        foreach ($fields as $field => $validate) {
            //we've to add permit_empty rule if the field is not required
            if (strpos($validate, 'required') !== false) {
                //this is required field
            } else {
                //so, this field isn't required, add permit_empty rule
                $validate .= "|permit_empty";
            }

            $final_fields[$field] = $validate;
        }

        if (!$final_fields) {
            //no fields to validate in this context, so nothing to validate
            return true;
        }

        $validate = $this->validate($final_fields);

        if (!$validate) {
            if (ENVIRONMENT === 'production') {
                $message = app_lang('something_went_wrong');
            } else {
                $validation = \Config\Services::validation();
                $message = $validation->getErrors();
            }

            if ($return_errors) {
                return $message;
            }

            echo json_encode(array("success" => false, 'message' => json_encode($message)));
            exit();
        }
    }

    /**
     * download files. If there is one file then don't archive the file otherwise archive the files.
     * 
     * @param string $directory_path
     * @param string $serialized_file_data 
     * @return download files
     */
    protected function download_app_files($directory_path, $serialized_file_data) {
        $file_exists = false;
        if ($serialized_file_data) {
            require_once(APPPATH . "ThirdParty/nelexa-php-zip/vendor/autoload.php");
            $zip = new \PhpZip\ZipFile();

            $files = unserialize($serialized_file_data);
            $total_files = count($files);

            //for only one file we'll download the file without archiving
            if ($total_files === 1) {
                helper('download');
            }

            $file_path = getcwd() . '/' . $directory_path;

            foreach ($files as $file) {
                $file_name = get_array_value($file, 'file_name');
                $output_filename = remove_file_prefix($file_name);
                $file_id = get_array_value($file, "file_id");
                $service_type = get_array_value($file, "service_type");

                if ($service_type == "google") {
                    //google drive file
                    $google = new Google();
                    $drive_file_data = $google->download_file($file_id);

                    //if there exists only one file then don't archive the file otherwise archive the file
                    if ($total_files === 1) {
                        return $this->response->download($output_filename, $drive_file_data);
                    } else {
                        $zip->addFromString($output_filename, $drive_file_data);
                        $file_exists = true;
                    }
                } else {
                    $path = $file_path . $file_name;
                    if (file_exists($path)) {

                        //if there exists only one file then don't archive the file otherwise archive the file
                        if ($total_files === 1) {
                            return $this->response->download($path, NULL)->setFileName($output_filename);
                        } else {

                            $zip->addFile($path, $output_filename);
                            $file_exists = true;
                        }
                    }
                }
            }
        }

        if ($file_exists) {
            $zip->outputAsAttachment(app_lang('download_zip_name') . '.zip');
            $zip->close();
        } else {
            die(app_lang("no_such_file_or_directory_found"));
        }
    }

}
