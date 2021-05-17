<?php

namespace App\Controllers;

class Signin extends App_Controller {

    private $signin_validation_errors;

    function __construct() {
        parent::__construct();
        $this->signin_validation_errors = array();
        helper('email');
    }

    function index() {
        if ($this->Users_model->login_user_id()) {
            app_redirect('dashboard/view');
        } else {

            $view_data["redirect"] = "";
            if (isset($_REQUEST["redirect"])) {
                $view_data["redirect"] = $_REQUEST["redirect"];
            }

            return $this->template->view('signin/index', $view_data);
        }
    }

    private function has_recaptcha_error() {
        $recaptcha_post_data = $this->request->getPost("g-recaptcha-response");
        $response = $this->is_valid_recaptcha($recaptcha_post_data);

        if ($response === true) {
            return true;
        } else {
            array_push($this->signin_validation_errors, app_lang("re_captcha_error-" . $response));
            return false;
        }
    }

    private function is_valid_recaptcha($recaptcha_post_data) {
        //load recaptcha lib
        require_once(APPPATH . "ThirdParty/recaptcha/autoload.php");
        $recaptcha = new \ReCaptcha\ReCaptcha(get_setting("re_captcha_secret_key"));
        $resp = $recaptcha->verify($recaptcha_post_data, $_SERVER['REMOTE_ADDR']);

        if ($resp->isSuccess()) {
            return true;
        } else {

            $error = "";
            foreach ($resp->getErrorCodes() as $code) {
                $error = $code;
            }

            return $error;
        }
    }

    // check authentication
    function authenticate() {
        $validation = $this->validate_submitted_data(array(
            "email" => "required|valid_email",
            "password" => "required"
                ), true);

        $email = $this->request->getPost("email");
        $password = $this->request->getPost("password");
        if (!$email) {
            //loaded the page directly
            app_redirect('signin');
        }

        if (is_array($validation)) {
            //has validation errors
            $this->signin_validation_errors = $validation;
        }

        //check if there reCaptcha is enabled
        //if reCaptcha is enabled, check the validation
        if (get_setting("re_captcha_secret_key")) {
            //in this function, if any error found in recaptcha, that will be added
            $this->has_recaptcha_error();
        }

        //don't check password if there is any error
        if ($this->signin_validation_errors) {
            $this->session->setFlashdata("signin_validation_errors", $this->signin_validation_errors);
            app_redirect('signin');
        }

        if (!$this->Users_model->authenticate($email, $password)) {
            //authentication failed
            array_push($this->signin_validation_errors, app_lang("authentication_failed"));
            $this->session->setFlashdata("signin_validation_errors", $this->signin_validation_errors);
            app_redirect('signin');
        }

        //authentication success
        $redirect = $this->request->getPost("redirect");
        if ($redirect) {
            return redirect()->to($redirect);
        } else {
            app_redirect('dashboard/view');
        }
    }

    function sign_out() {
        $this->Users_model->sign_out();
    }

    //send an email to users mail with reset password link
    function send_reset_password_mail() {
        $this->validate_submitted_data(array(
            "email" => "required|valid_email"
        ));


        //check if there reCaptcha is enabled
        //if reCaptcha is enabled, check the validation
        if (get_setting("re_captcha_secret_key")) {

            $response = $this->is_valid_recaptcha($this->request->getPost("g-recaptcha-response"));

            if ($response !== true) {

                if ($response) {
                    echo json_encode(array('success' => false, 'message' => app_lang("re_captcha_error-" . $response)));
                } else {
                    echo json_encode(array('success' => false, 'message' => app_lang("re_captcha_expired")));
                }

                return false;
            }
        }



        $email = $this->request->getPost("email");

        $existing_user = $this->Users_model->is_email_exists($email);

        //send reset password email if found account with this email
        if ($existing_user) {
            $email_template = $this->Email_templates_model->get_final_template("reset_password");

            $parser_data["ACCOUNT_HOLDER_NAME"] = $existing_user->first_name . " " . $existing_user->last_name;
            $parser_data["SIGNATURE"] = $email_template->signature;
            $parser_data["LOGO_URL"] = get_logo_url();
            $parser_data["SITE_URL"] = get_uri();

            $verification_data = array(
                "type" => "reset_password",
                "code" => make_random_string(),
                "params" => serialize(array(
                    "email" => $existing_user->email,
                    "expire_time" => time() + (24 * 60 * 60)
                ))
            );

            $save_id = $this->Verification_model->ci_save($verification_data);

            $verification_info = $this->Verification_model->get_one($save_id);

            $parser_data['RESET_PASSWORD_URL'] = get_uri("signin/new_password/" . $verification_info->code);

            $message = $this->parser->setData($parser_data)->renderString($email_template->message);
            if (send_app_mail($email, $email_template->subject, $message)) {
                echo json_encode(array('success' => true, 'message' => app_lang("reset_info_send")));
            } else {
                echo json_encode(array('success' => false, 'message' => app_lang('error_occurred')));
            }
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang("no_acount_found_with_this_email")));
            return false;
        }
    }

    //show forgot password recovery form
    function request_reset_password() {
        $view_data["form_type"] = "request_reset_password";
        return $this->template->view('signin/index', $view_data);
    }

    //when user clicks to reset password link from his/her email, redirect to this url
    function new_password($key) {
        $valid_key = $this->is_valid_reset_password_key($key);

        if ($valid_key) {
            $email = get_array_value($valid_key, "email");

            if ($this->Users_model->is_email_exists($email)) {
                $view_data["key"] = $key;
                $view_data["form_type"] = "new_password";
                return $this->template->view('signin/index', $view_data);
                return false;
            }
        }

        //else show error
        $view_data["heading"] = "Invalid Request";
        $view_data["message"] = "The key has expaired or something went wrong!";
        return $this->template->view("errors/html/error_general", $view_data);
    }

    //finally reset the old password and save the new password
    function do_reset_password() {

        $this->validate_submitted_data(array(
            "key" => "required",
            "password" => "required"
        ));


        $key = $this->request->getPost("key");
        $password = $this->request->getPost("password");
        $valid_key = $this->is_valid_reset_password_key($key);

        if ($valid_key) {
            $email = get_array_value($valid_key, "email");
            $user = $this->Users_model->is_email_exists($email);
            $user_data = array("password" => password_hash($password, PASSWORD_DEFAULT));
            if ($user->id && $this->Users_model->ci_save($user_data, $user->id)) {
                //user can't reset password two times with the same code
                $options = array("code" => $key, "type" => "reset_password");
                $verification_info = $this->Verification_model->get_details($options)->getRow();
                if ($verification_info->id) {
                    $this->Verification_model->delete_permanently($verification_info->id);
                }

                echo json_encode(array("success" => true, 'message' => app_lang("password_reset_successfully") . " " . anchor("signin", app_lang("signin"))));
                return true;
            }
        }
        echo json_encode(array("success" => false, 'message' => app_lang("error_occurred")));
    }

    //check valid key
    private function is_valid_reset_password_key($verification_code = "") {

        if ($verification_code) {
            $options = array("code" => $verification_code, "type" => "reset_password");
            $verification_info = $this->Verification_model->get_details($options)->getRow();

            if ($verification_info && $verification_info->id) {
                $reset_password_info = unserialize($verification_info->params);

                $email = get_array_value($reset_password_info, "email");
                $expire_time = get_array_value($reset_password_info, "expire_time");

                if ($email && filter_var($email, FILTER_VALIDATE_EMAIL) && $expire_time && $expire_time > time()) {
                    return array("email" => $email);
                }
            }
        }
    }

}
