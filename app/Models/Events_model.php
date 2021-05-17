<?php

namespace App\Models;

class Events_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'events';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $events_table = $this->db->prefixTable('events');
        $users_table = $this->db->prefixTable('users');
        $clients_table = $this->db->prefixTable('clients');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $events_table.id=$id";
        }

        $start_date_query = "";
        $end_date_query = "";

        $start_date = get_array_value($options, "start_date");
        if ($start_date) {
            $start_date = $this->db->escapeString($start_date);
            $start_date_query = " DATE($events_table.start_date)>='$start_date'";
        }

        $end_date = get_array_value($options, "end_date");
        if ($end_date) {
            $end_date = $this->db->escapeString($end_date);
            $end_date_query = " DATE($events_table.end_date)<='$end_date'";
        }

        //when we'll find event by date, we also have to find the recurring events
        $include_recurring = get_array_value($options, "include_recurring");
        if ($include_recurring) {
            $where .= " AND (( " . $start_date_query . " AND " . $end_date_query . ") OR $events_table.recurring = 1) ";
        } else if ($start_date_query && $end_date_query) {
            $where .= " AND " . $start_date_query . " AND " . $end_date_query;
        }


        $future_from = get_array_value($options, "future_from");
        if ($future_from) {
            $where .= " AND (DATE($events_table.start_date)>='$future_from' OR DATE($events_table.last_start_date)>='$future_from' )";
        }

        $user_id = get_array_value($options, "user_id");
        if ($user_id) {

            //find events where share with the user and his/her team
            $team_ids = get_array_value($options, "team_ids");
            $team_search_sql = "";

            //searh for teams
            if ($team_ids) {
                $teams_array = explode(",", $team_ids);
                foreach ($teams_array as $team_id) {
                    $team_search_sql .= " OR (FIND_IN_SET('team:$team_id', $events_table.share_with)) ";
                }
            }


            $is_client = get_array_value($options, "is_client");
            if ($is_client) {
                //client user's can't see the events which has shared with all team members
                $where .= " AND ($events_table.created_by=$user_id OR (FIND_IN_SET('contact:$user_id', $events_table.share_with)))";
            } else {
                //searh for user and teams
                $where .= " AND ($events_table.created_by=$user_id 
                OR $events_table.share_with='all'
                    OR (FIND_IN_SET('member:$user_id', $events_table.share_with))
                        $team_search_sql
                        )";
            }

            $where .= " AND $users_table.deleted=0 AND $users_table.status='active'";
        }

        $client_id = get_array_value($options, "client_id");
        if ($client_id) {
            $where .= " AND $events_table.client_id=$client_id";
        }

        $label_id = get_array_value($options, "label_id");
        if ($label_id) {
            $where .= " AND FIND_IN_SET($label_id, $events_table.labels) ";
        }

        $limit = get_array_value($options, "limit");
        $limit = $limit ? $limit : "20000";
        $offset = get_array_value($options, "offset");
        $offset = $offset ? $offset : "0";

        $select_labels_data_query = $this->get_labels_data_query();

        $sql = "SELECT $events_table.*,
            CONCAT($users_table.first_name, ' ',$users_table.last_name) AS created_by_name, $users_table.image AS created_by_avatar, $clients_table.company_name, $clients_table.is_lead, $select_labels_data_query
        FROM $events_table
        LEFT JOIN $users_table ON $users_table.id = $events_table.created_by
        LEFT JOIN $clients_table ON $clients_table.id = $events_table.client_id    
        WHERE $events_table.deleted=0 $where
        ORDER BY $events_table.start_date ASC
        LIMIT $offset, $limit";
        return $this->db->query($sql);
    }

    function count_events_today($options = array()) {
        $events_table = $this->db->prefixTable('events');
        $now = get_my_local_time("Y-m-d");

        $where = "";
        $user_id = get_array_value($options, "user_id");
        if ($user_id) {

            //find events where share with the user and his/her team
            $team_ids = get_array_value($options, "team_ids");
            $team_search_sql = "";

            //searh for teams
            if ($team_ids) {
                $teams_array = explode(",", $team_ids);
                foreach ($teams_array as $team_id) {
                    $team_search_sql .= " OR (FIND_IN_SET('team:$team_id', $events_table.share_with)) ";
                }
            }

            $is_client = get_array_value($options, "is_client");
            if ($is_client) {
                //client user's can't see the events which has shared with all team members
                $where .= " AND ($events_table.created_by=$user_id OR (FIND_IN_SET('contact:$user_id', $events_table.share_with)))";
            } else {
                //searh for user and teams
                $where .= " AND ($events_table.created_by=$user_id 
                OR $events_table.share_with='all' 
                    OR (FIND_IN_SET('member:$user_id', $events_table.share_with))
                        $team_search_sql
                        )";
            }
        }

        $sql = "SELECT COUNT($events_table.id) AS total
        FROM $events_table
        WHERE $events_table.deleted=0 AND (($events_table.start_date<='$now' AND $events_table.end_date>='$now') OR FIND_IN_SET('$now',$events_table.recurring_dates)) $where";

        return $this->db->query($sql)->getRow()->total;
    }

    function get_label_suggestions() {
        $events_table = $this->db->prefixTable('events');
        $sql = "SELECT GROUP_CONCAT(labels) AS label_groups
        FROM $events_table
        WHERE $events_table.deleted=0";
        return $this->db->query($sql)->getRow()->label_groups;
    }

    function get_no_of_cycles($repeat_type, $no_of_cycles = 0) {
        if ($repeat_type === "days") {
            //for days type repeating, max value can't be more then 365 days
            if (!$no_of_cycles || $no_of_cycles > 365) {
                $no_of_cycles = 365;
            }
        } else if ($repeat_type === "weeks") {
            //for weeks type repeating, max value can't be more then 520 weeks
            if (!$no_of_cycles || $no_of_cycles > 520) {
                $no_of_cycles = 520;
            }
        } else if ($repeat_type === "months") {
            //for months type repeating, max value can't be more then 120 monts
            if (!$no_of_cycles || $no_of_cycles > 120) {
                $no_of_cycles = 120;
            }
        } else if ($repeat_type === "years") {
            //for days type years, max value can't be more then 10 years
            if (!$no_of_cycles || $no_of_cycles > 10) {
                $no_of_cycles = 10;
            }
        }

        return $no_of_cycles;
    }

    private function sort_by_start_date($a, $b) {
        return strtotime($a->start_date) - strtotime($b->start_date);
    }

    function get_upcomming_events($options = array()) {

        //find all event after today
        $today = date("Y-m-d", strtotime(convert_date_local_to_utc(date("Y-m-d H:i:s"))) + get_timezone_offset());
        $options["future_from"] = $today;
        $result = $this->get_details($options)->getResult();

        $final_result = array();
        $has_recurring = false;

        foreach ($result as $data) {

            $data->cycle = 0; //recured to calculate the recurring dates

            if ($data->recurring) {
                $has_recurring = true;

                //include only future date
                if ($data->start_date >= $today) {
                    $final_result[] = clone $data;
                }

                //prepare all rows base on recurring info

                $no_of_cycles = $this->get_no_of_cycles($data->repeat_type, $data->no_of_cycles);

                for ($i = 1; $i <= $no_of_cycles; $i++) {

                    $data->start_date = add_period_to_date($data->start_date, $data->repeat_every, $data->repeat_type);
                    $data->end_date = add_period_to_date($data->end_date, $data->repeat_every, $data->repeat_type);
                    $data->cycle = $i;

                    //include only the rows which start date after today
                    if ($data->start_date >= $today) {
                        $final_result[] = clone $data;
                    }
                }
            } else {
                $final_result[] = $data; //add regulary event
            }
        }


        //if there are recurring events, so we have to re-sort the events and remove extra rows
        if ($has_recurring) {
            usort($final_result, array($this, "sort_by_start_date")); //sort by start date
            $final_result = array_slice($final_result, 0, 10); //keep only top 10 rows
        }


        return $final_result;
    }

    function get_response_by_users($user_ids_array = array()) {
        $users_table = $this->db->prefixTable('users');
        $user_ids = implode(",", $user_ids_array);

        if ($user_ids) {
            $sql = "SELECT $users_table.id,  $users_table.user_type, $users_table.image, CONCAT($users_table.first_name, ' ',$users_table.last_name) AS member_name FROM $users_table WHERE (FIND_IN_SET($users_table.id, '$user_ids')) AND deleted=0";

            return $this->db->query($sql);
        } else {
            return false;
        }
    }

    function save_event_status($id, $user_id, $status) {
        $events_table = $this->db->prefixTable('events');

        $new_status = "";
        $old_status = "";

        if ($status == "confirmed") {
            $new_status .= "$events_table.confirmed_by";
            $old_status .= "$events_table.rejected_by";
        } else if ($status == "rejected") {
            $new_status .= "$events_table.rejected_by";
            $old_status .= "$events_table.confirmed_by";
        }

        $sql = "UPDATE $events_table SET $new_status = CONCAT($new_status,',',$user_id), $old_status = REPLACE($old_status,',$user_id','')
                WHERE $events_table.id=$id AND FIND_IN_SET($user_id,$new_status) = 0";

        return $this->db->query($sql);
    }

    function get_share_with_users_of_event($event_info = "") {
        if ($event_info) {

            $users_table = $this->db->prefixTable('users');
            $team_table = $this->db->prefixTable('team');

            $where = "";

            if ($event_info->share_with === "all") {
                $where .= " AND $users_table.user_type = 'staff' "; //all team members
            } else {
                $share_with_array = explode(",", $event_info->share_with); // found an array like this array("member:1", "member:2", "team:1")

                $event_users = array();
                $event_team = array();
                $event_contact = array();

                foreach ($share_with_array as $share) {

                    $share_data = explode(":", $share);

                    if (get_array_value($share_data, '0') === "member") {
                        $event_users[] = get_array_value($share_data, '1');
                    } else if (get_array_value($share_data, '0') === "team") {
                        $event_team[] = get_array_value($share_data, '1');
                    } else if (get_array_value($share_data, '0') === "contact") {
                        $event_contact[] = get_array_value($share_data, '1');
                    }
                }

                //find team members
                if (count($event_users)) {
                    $where .= " AND FIND_IN_SET($users_table.id, '" . join(',', $event_users) . "') ";
                }

                //find team
                if (count($event_team)) {
                    $where .= " AND FIND_IN_SET($users_table.id, (SELECT GROUP_CONCAT($team_table.members) AS team_users FROM $team_table WHERE $team_table.deleted=0 AND FIND_IN_SET($team_table.id, '" . join(',', $event_team) . "'))) ";
                }

                //find client contacts
                if (count($event_contact)) {
                    $where .= " AND FIND_IN_SET($users_table.id, '" . join(',', $event_contact) . "') ";
                }
            }

            $sql = "SELECT $users_table.email FROM $users_table
                WHERE $users_table.deleted=0 AND $users_table.status='active' AND $users_table.id!=$event_info->created_by $where";

            return $this->db->query($sql);
        }
    }

    function get_integrated_users_with_google_calendar() {
        $settings_table = $this->db->prefixTable('settings');

        $sql = "SELECT $settings_table.setting_name, $settings_table.setting_value FROM $settings_table
                WHERE $settings_table.deleted=0 AND $settings_table.setting_name LIKE '%_integrate_with_google_calendar%' AND $settings_table.setting_value=1";

        return $this->db->query($sql);
    }

}
