<?php

namespace App\Controllers;

use App\Libraries\Google;
use App\Libraries\Google_calendar;

class Google_api extends Security_Controller {

    function __construct() {
        parent::__construct();
        $this->google = new Google();
        $this->google_calendar = new Google_calendar();
    }

    function index() {
        app_redirect("google_api/authorize");
    }

    //authorize google drive
    function authorize() {
        $this->access_only_admin();
        $this->google->authorize();
    }

    //get access token of drive and save
    function save_access_token() {
        $this->access_only_admin();

        if (!empty($_GET)) {
            $this->google->save_access_token(get_array_value($_GET, 'code'));
            app_redirect("settings/integration/google_drive");
        }
    }

    //authorize google calendar
    function authorize_calendar() {
        $this->google_calendar->authorize($this->login_user->id);
    }

    //get access code and save
    function save_access_token_of_calendar() {
        if (!empty($_GET)) {
            $this->google_calendar->save_access_token(get_array_value($_GET, 'code'), $this->login_user->id);
            app_redirect("events");
        }
    }

}

/* End of file Google_api.php */
/* Location: ./app/controllers/Google_api.php */