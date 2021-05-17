<?php

namespace App\Libraries;

use App\Controllers\App_Controller;

class Google_calendar {

    private $ci;

    public function __construct() {
        $this->ci = new App_Controller();

        //load resources
        require_once(APPPATH . "ThirdParty/Google/google-api-php-client/vendor/autoload.php");
    }

    //authorize connection
    public function authorize($user_id = "") {
        $client = $this->_get_client_credentials($user_id);
        $this->_check_access_token($client, $user_id, true);
    }

    //check access token
    private function _check_access_token($client, $user_id = "", $redirect_to_settings = false) {
        //load previously authorized token from database, if it exists.
        $accessToken = get_setting('user_' . $user_id . '_oauth_access_token');

        if ($accessToken && get_setting('user_' . $user_id . '_google_calendar_authorized')) {
            $client->setAccessToken(json_decode($accessToken, true));
        }

        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                $this->_check_calendar_ids($client, $user_id);
                if ($redirect_to_settings) {
                    app_redirect("events");
                }
            } else {
                $authUrl = $client->createAuthUrl();
                app_redirect($authUrl, true);
            }
        } else {
            $this->_check_calendar_ids($client, $user_id);
            if ($redirect_to_settings) {
                app_redirect("events");
            }
        }
    }

    //verify if there has any additional calendar id
    private function _check_calendar_ids($client, $user_id = "") {
        $calendar_ids = get_setting("user_" . $user_id . "_calendar_ids");
        if (!$calendar_ids) { //check if user have any additional calendar
            return true;
        }

        $calendar_ids_array = unserialize($calendar_ids);
        if (!count($calendar_ids_array)) {
            return true;
        }

        $service = new \Google_Service_Calendar($client); //we got the associated user, so directly get the service
        foreach ($calendar_ids_array as $calendar_id) {
            try {
                $service->events->listEvents("primary", array("calendarId" => $calendar_id));
                continue; //this calendar id is valid, skip to the next loop
            } catch (Exception $ex) {
                //so the calendar id isn't valid
                //redirect with error message
                $this->ci->Settings_model->save_setting('user_' . $user_id . '_google_calendar_authorized', "0");
                $session = \Config\Services::session();
                $session->setFlashdata("error_message", app_lang('invalid_calendar_id_error_message') . ": " . $calendar_id);
                app_redirect("events");
            }
        }
    }

    //fetch access token with auth code and save to database
    public function save_access_token($auth_code, $user_id = "") {
        $client = $this->_get_client_credentials($user_id);

        // Exchange authorization code for an access token.
        $accessToken = $client->fetchAccessTokenWithAuthCode($auth_code);

        $error = get_array_value($accessToken, "error");
        if ($error)
            die($error);

        $client->setAccessToken($accessToken);

        // Save the token to database
        $new_access_token = json_encode($client->getAccessToken());

        if ($new_access_token) {
            $this->_check_calendar_ids($client, $user_id);

            $this->ci->Settings_model->save_setting('user_' . $user_id . '_oauth_access_token', $new_access_token);

            //got the valid access token. store to setting that it's authorized
            $this->ci->Settings_model->save_setting('user_' . $user_id . '_google_calendar_authorized', "1");
        }
    }

    //get client credentials
    private function _get_client_credentials($user_id = "") {
        $url = get_uri("google_api/save_access_token_of_calendar");

        $client = new \Google_Client();
        $client->setApplicationName(get_setting('app_title'));
        $client->setRedirectUri($url);
        $client->setAccessType("offline");
        $client->setPrompt('select_account consent');
        $client->setClientId(get_setting('user_' . $user_id . '_google_client_id'));
        $client->setClientSecret(get_setting('user_' . $user_id . '_google_client_secret'));
        $client->setScopes(\Google_Service_Calendar::CALENDAR);

        return $client;
    }

    //get google calendar service
    private function _get_calendar_service($user_id = 0) {
        $client = $this->_get_client_credentials($user_id);
        $this->_check_access_token($client, $user_id);

        return new \Google_Service_Calendar($client);
    }

    //add/update the event of google calendar
    public function save_event($user_id = 0, $id = 0) {
        if ($user_id && $id && get_setting("enable_google_calendar_api") && get_setting("module_event")) {
            $event_info = $this->ci->Events_model->get_one($id);

            if ($event_info) {
                //prepare data
                $calendar_event_info = new \stdClass();
                $service = $this->_get_calendar_service($user_id);

                //prepare date-time object type
                //start and end times must either both be date or both be dateTime (as per google calendar api policies)
                $datetime_object_type = "dateTime";
                if ($event_info->start_time == "00:00:00") {
                    $datetime_object_type = "date";
                }

                $event = new \Google_Service_Calendar_Event(array(
                    'summary' => $event_info->title,
                    'location' => $event_info->location,
                    'description' => $event_info->description,
                    'start' => $this->_get_start_end_date_time($event_info, "start", $datetime_object_type),
                    'end' => $this->_get_start_end_date_time($event_info, "end", $datetime_object_type),
                    'recurrence' => $this->_get_recurrence_data_for_google($event_info),
                    'attendees' => $this->_get_share_with_emails($event_info),
                    'transparency' => "transparent", //show as available on the event
                    'reminders' => array(
                        'useDefault' => FALSE, //we've to add this functionality after adding the reminder of events
                    )
                ));

                $calendarId = 'primary'; //insert to own google calendar only

                if ($event_info->google_event_id && $event_info->editable_google_event) {
                    //update operation
                    $calendar_event_info = $service->events->update($calendarId, $event_info->google_event_id, $event);
                } else if (!$event_info->google_event_id) {
                    //insert operation
                    $calendar_event_info = $service->events->insert($calendarId, $event);
                }

                //save newly added event information
                if (count($calendar_event_info)) {
                    $user_info = $this->ci->Users_model->get_one($user_id);
                    $google_event_id = $calendar_event_info->recurringEventId ? $calendar_event_info->recurringEventId : $calendar_event_info->id;
                    $data = array(
                        "google_event_id" => $google_event_id,
                        "editable_google_event" => $user_info->email == $calendar_event_info->creator->email ? 1 : 0
                    );
                    $this->ci->Events_model->ci_save($data, $id);
                }
            }
        }
    }

    //get start/end date and time 
    private function _get_start_end_date_time($event_info, $type = "", $datetime_object_type = "") {
        $date_object = $type . "_date";
        $time_object = $type . "_time";

        $time_array = array("timeZone" => get_setting("timezone"));

        if ($datetime_object_type == "date") { //all day event
            $time_array["date"] = $event_info->$date_object;
        } else { //customized time event
            $date_time = new \DateTime($event_info->$date_object . " " . $event_info->$time_object, new \DateTimeZone(get_setting("timezone")));
            $time_array["dateTime"] = $date_time->format(\DateTime::RFC3339);
        }

        return $time_array;
    }

    //get recurrence line for recurring event
    private function _get_recurrence_data_for_google($event_info = "") {
        if ($event_info && $event_info->recurring) {
            $repeat_type = "DAILY";
            if ($event_info->repeat_type == "weeks") {
                $repeat_type = "WEEKLY";
            } else if ($event_info->repeat_type == "months") {
                $repeat_type = "MONTHLY";
            } else if ($event_info->repeat_type == "years") {
                $repeat_type = "YEARLY";
            }

            return array("RRULE:FREQ=" . $repeat_type . ";UNTIL=" . gmdate("Ymd\THis\Z", strtotime($event_info->last_start_date)) . ";INTERVAL=" . $event_info->repeat_every);
        } else {
            return array();
        }
    }

    //get email address of users whose are shared with this event
    private function _get_share_with_emails($event_info = "") {
        if ($event_info && $event_info->share_with) {
            $emails_array = array();

            $users = $this->ci->Events_model->get_share_with_users_of_event($event_info)->getResult();
            foreach ($users as $user) {
                if ($user->email) {
                    $emails_array[] = array("email" => $user->email);
                }
            }

            return $emails_array;
        } else {
            return array();
        }
    }

    //delete event
    public function delete($google_event_id = "", $user_id = "") {
        if ($google_event_id && $user_id) {
            $service = $this->_get_calendar_service($user_id);
            $service->events->delete('primary', $google_event_id);
        }
    }

    //get google calendar events
    public function get_google_calendar_events() {
        if (get_setting("enable_google_calendar_api") && get_setting("module_event")) {
            $enabled_users_settings = $this->ci->Events_model->get_integrated_users_with_google_calendar()->getResult();

            foreach ($enabled_users_settings as $setting) {
                $user_id = $setting->setting_name;
                $user_id = explode("_", $user_id);
                $user_id = get_array_value($user_id, 1);

                if ($user_id && get_setting('user_' . $user_id . '_oauth_access_token') && get_setting('user_' . $user_id . '_google_calendar_authorized')) {

                    //check user's deleted status
                    $user_options = array("id" => $user_id);
                    $user_info = $this->ci->Users_model->get_details($user_options)->getRow();
                    if (!$user_info->id) {
                        continue;
                    }

                    //there has access token for this user
                    $service = $this->_get_calendar_service($user_id);

                    $event_options = array(
                        'orderBy' => 'startTime',
                        'singleEvents' => true,
                        'timeMin' => date('c'),
                        'showDeleted' => true
                    );

                    //check if this user has any calendar id/s
                    //if found, get events of all calendar id/s
                    //otherwise get only his primary events
                    $calendar_ids = get_setting("user_" . $user_id . "_calendar_ids");
                    if ($calendar_ids) {
                        $calendar_ids_array = unserialize($calendar_ids);
                        if (count($calendar_ids_array)) {
                            foreach ($calendar_ids_array as $calendar_id) {
                                if ($calendar_id != $user_info->email) { //user's private calender id and his email is same
                                    $event_options["calendarId"] = $calendar_id;

                                    $results = $service->events->listEvents("primary", $event_options);
                                    $events = $results->getItems();

                                    $this->_prepare_calendar_events($events, $user_id);
                                }
                            }
                        }
                    }

                    //unset calenderId
                    unset($event_options["calendarId"]);

                    //get user's own events
                    $results = $service->events->listEvents("primary", $event_options);
                    $events = $results->getItems();

                    $this->_prepare_calendar_events($events, $user_id);
                }
            }
        }
    }

    //prepare calendar events
    private function _prepare_calendar_events($events = array(), $user_id = 0) {
        $recurring_event_ids_array = array();
        $user_info = $this->ci->Users_model->get_access_info($user_id);

        //create/get google calendar label
        $label_data = array("title" => app_lang("google_calendar_event"), "color" => "#2d9cdb", "context" => "event", "user_id" => $user_id);
        $existing_label = $this->ci->Labels_model->get_one_where(array_merge($label_data, array("deleted" => 0)));
        if ($existing_label->id) {
            $label_id = $existing_label->id;
        } else {
            $label_id = $this->ci->Labels_model->ci_save($label_data);
        }

        if (!$events || !count($events)) {
            return false;
        }

        foreach ($events as $event) {
            $google_event_id = $event->recurringEventId ? $event->recurringEventId : $event->id;

            //if the event is deleted from Google, delete from RISE too
            if ($event->status == "cancelled") {
                $this->_delete_calendar_events($user_id, $google_event_id);
                continue;
            }

            $start_date = $event->start->date;
            $end_date = $start_date; //start date and end date should be same for one time event
            $start_time = "00:00:00";
            $end_time = "00:00:00";

            if (!$start_date) {
                //there has different start/end date or time there
                $main_start_date = new \DateTime($event->start->dateTime);
                $main_end_date = new \DateTime($event->end->dateTime);

                //convert to local timezone of the app
                $timezone = get_setting("timezone");
                $main_start_date->setTimezone(new \DateTimeZone($timezone));
                $main_end_date->setTimezone(new \DateTimeZone($timezone));

                //get start date and time
                $start_date = $main_start_date->format('Y-m-d');
                $start_time = $main_start_date->format('H:i:s');

                //get end date and time
                $end_date = $main_end_date->format('Y-m-d');
                $end_time = $main_end_date->format('H:i:s');
            }

            //prepare attendees
            $share_with = "";
            $permissions = array();
            if ($user_info->permissions) {
                $unserialize_permissions = unserialize($user_info->permissions);
                if (is_array($unserialize_permissions)) {
                    $permissions = $unserialize_permissions;
                }
            }
            if ($user_info->user_type == "staff" && !get_array_value($permissions, "disable_event_sharing") && $event->attendees && count($event->attendees)) {

                foreach ($event->attendees as $attendee) {
                    if ($attendee->email != $user_info->email) {

                        //find the user associated with the email
                        $share_with_user_info = $this->ci->Users_model->get_one_where(array("email" => $attendee->email, "deleted" => 0));
                        if ($share_with_user_info->id) {
                            $content = "member:$share_with_user_info->id";
                            if ($share_with_user_info->user_type == "client") {
                                $content = "contact:$share_with_user_info->id";
                            }

                            if ($share_with) {
                                $share_with .= ",";
                            }
                            $share_with .= $content;
                        }
                    }
                }
            }

            $data = array(
                "title" => $event->summary,
                "description" => $event->description,
                "start_date" => $start_date,
                "end_date" => $end_date,
                "start_time" => $start_time,
                "end_time" => $end_time,
                "location" => $event->location,
                "created_by" => $user_id,
                "client_id" => 0,
                "google_event_id" => $google_event_id,
                "labels" => $label_id,
                "editable_google_event" => $user_info->email == $event->creator->email ? 1 : 0, //check if user has permission to modify this event
                "share_with" => $share_with
            );

            //get recurring data
            $recurring_id = $event->recurringEventId;
            if ($recurring_id && !in_array($recurring_id, $recurring_event_ids_array)) {
                $recurring_dates = $this->_prepare_recurring_event_data($events, $recurring_id);

                $data["recurring"] = 1;
                $data["last_start_date"] = end($recurring_dates);
                $data["recurring_dates"] = implode(",", $recurring_dates);
                $data["no_of_cycles"] = count($recurring_dates);

                $repeats_data = $this->_get_repeats_of_recurring_event($recurring_dates);

                $data["repeat_type"] = get_array_value($repeats_data, "repeat_type");
                $data["repeat_every"] = get_array_value($repeats_data, "repeat_every");

                $data = clean_data($data);
                $this->_save_calendar_events($data, $google_event_id);

                //store recurring event id
                array_push($recurring_event_ids_array, $recurring_id);
            } else if (!$recurring_id) {
                $data = clean_data($data);
                $this->_save_calendar_events($data, $google_event_id);
            }
        }
    }

    //get repeat type of recurring event
    private function _get_repeats_of_recurring_event($dates = array()) {
        $repeat_type = "";
        $repeat_every = "";

        $start_date = get_array_value($dates, "0");
        $end_date = get_array_value($dates, "1");

        $distance = get_date_difference_in_days($start_date, $end_date);

        if ($distance < 7) {
            //days
            $repeat_type = "days";
            $repeat_every = $distance;
        } else {
            $diff_weeks = $distance / 7;
            if (is_int($diff_weeks)) {
                //weeks
                $repeat_type = "weeks";
                $repeat_every = $diff_weeks;
            } else {
                //get months
                $start_date_str = strtotime($start_date);
                $end_date_str = strtotime($end_date);

                $start_month = date('m', $start_date_str);
                $end_month = date('m', $end_date_str);

                $start_year = date('Y', $start_date_str);
                $end_year = date('Y', $end_date_str);

                $months = (($end_year - $start_year) * 12) + ($end_month - $start_month);

                $diff_years = $months / 12;

                if (is_int($diff_years)) {
                    //years
                    $repeat_type = "years";
                    $repeat_every = $diff_years;
                } else {
                    //months
                    $repeat_type = "months";
                    $repeat_every = $months;
                }
            }
        }

        return array("repeat_type" => $repeat_type, "repeat_every" => $repeat_every);
    }

    //prepare recurring event data
    private function _prepare_recurring_event_data($events, $recurring_event_id = "") {
        if ($recurring_event_id && $events && count($events)) {

            $recurring_dates = array();
            $substract_one_day = true;

            foreach ($events as $event) {
                if ($event->recurringEventId && $event->recurringEventId == $recurring_event_id) {
                    $date = $event->start->date;
                    if (!$date) {
                        //there has different start/end date or time there
                        $main_start_date = new \DateTime($event->start->dateTime);

                        $timezone = get_setting("timezone");
                        $main_start_date->setTimezone(new \DateTimeZone($timezone));

                        $date = $main_start_date->format('Y-m-d');
                        $substract_one_day = false;
                    }

                    array_push($recurring_dates, $date);
                }
            }

            if ($substract_one_day) {
                array_pop($recurring_dates); //google is adding one day for 'whole day event'
            }

            return $recurring_dates;
        }
    }

    //delete calendar events
    private function _delete_calendar_events($user_id = 0, $google_event_id = "") {
        $existing_event = $this->ci->Events_model->get_one_where(array("google_event_id" => $google_event_id, "created_by" => $user_id));
        if ($existing_event->id) {
            $this->ci->Events_model->delete($existing_event->id);
        }
    }

    //save calendar events
    private function _save_calendar_events($data = array(), $google_event_id = "") {
        //save new event/ update existing event
        //we've to skip the addition of un-editable google event which has deleted from local. that's why find the deleted row too
        $existing_event = $this->ci->Events_model->get_one_where(array("google_event_id" => $google_event_id, "created_by" => get_array_value($data, "created_by")));
        $this->ci->Events_model->ci_save($data, $existing_event->id);
    }

    public function get_event_link($google_event_id, $user_id) {
        $service = $this->_get_calendar_service($user_id);
        $event = $service->events->get('primary', $google_event_id);

        return $event->htmlLink ? $event->htmlLink : false;
    }

}
