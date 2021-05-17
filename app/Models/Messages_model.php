<?php

namespace App\Models;

class Messages_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'messages';
        parent::__construct($this->table);
    }

    /*
     * prepare details info of a message
     */

    function get_details($options = array()) {
        $messages_table = $this->db->prefixTable('messages');
        $users_table = $this->db->prefixTable('users');

        $mode = get_array_value($options, "mode");

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $messages_table.id=$id";
        }

        $message_id = get_array_value($options, "message_id");
        if ($message_id) {
            $where .= " AND $messages_table.message_id=$message_id";
        }

        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND ($messages_table.from_user_id=$user_id OR $messages_table.to_user_id=$user_id) ";
        }


        $join_with = "$messages_table.from_user_id";
        $join_another = "$messages_table.to_user_id";
        if ($user_id && $mode === "inbox") {
            $where .= " AND $messages_table.message_id=0 ";
        } else if ($user_id && $mode === "sent_items") {
            $where .= " AND $messages_table.message_id=0 ";
            $join_with = "$messages_table.to_user_id";
            $join_another = "$messages_table.from_user_id";
        }

        $last_message_id = get_array_value($options, "last_message_id");
        if ($last_message_id) {
            $where .= " AND $messages_table.id>$last_message_id";
        }


        $top_message_id = get_array_value($options, "top_message_id");
        if ($top_message_id) {
            $where .= " AND $messages_table.id<$top_message_id";
        }



        $limit = get_array_value($options, "limit");
        $limit = $limit ? $limit : "30";
        $offset = get_array_value($options, "offset");
        $offset = $offset ? $offset : "0";

        $sql = "SELECT * FROM (SELECT 0 AS reply_message_id, $messages_table.*, CONCAT($users_table.first_name, ' ', $users_table.last_name) AS user_name, $users_table.image AS user_image, $users_table.user_type, CONCAT(another_user.first_name, ' ', another_user.last_name) AS another_user_name, another_user.id AS another_user_id, another_user.last_online AS another_user_last_online
        FROM $messages_table
        LEFT JOIN $users_table ON $users_table.id=$join_with
        LEFT JOIN $users_table AS another_user ON another_user.id=$join_another
        WHERE $messages_table.deleted=0 $where
        ORDER BY $messages_table.id DESC  LIMIT $offset, $limit) new_message ORDER BY id ASC";

        $query = $this->db->query($sql);

        $data = new \stdClass();
        $data->result = $query->getResult();
        $data->row = $query->getRow();
        $data->found_rows = 0;

        if ($message_id) {
            $data->found_rows = $this->db->query("SELECT COUNT(id) AS found_rows FROM $messages_table WHERE $messages_table.message_id = $message_id")->getRow()->found_rows;
        }

        return $data;
    }

    /*
     * prepare inbox/sent items list
     */

    function get_list($options = array()) {
        $messages_table = $this->db->prefixTable('messages');
        $users_table = $this->db->prefixTable('users');

        $mode = get_array_value($options, "mode");
        $user_id = get_array_value($options, "user_id");

        if ($user_id && $mode === "inbox") {
            $where_user = "to_user_id";
            $select_user = "from_user_id";
        } else if ($user_id && $mode === "sent_items") {
            $where_user = "from_user_id";
            $select_user = "to_user_id";
        }

        $where = "";
        $user_ids = get_array_value($options, "user_ids");
        if ($user_ids) {
            $where .= " AND $messages_table.$select_user IN($user_ids)";
        }

        //ignor sql mode here 
        $this->db->query("SET sql_mode = ''");


        $sql = "SELECT  y.*, $messages_table.status, $messages_table.created_at, $messages_table.files,
                CONCAT($users_table.first_name, ' ', $users_table.last_name) AS user_name, $users_table.image AS user_image, $users_table.last_online
                FROM (
                    SELECT max(x.id) as id, main_message_id,  subject, IF(subject='', (SELECT subject FROM $messages_table WHERE id=main_message_id) ,'') as reply_subject, $select_user
                        FROM (SELECT id, IF(message_id=0,id,message_id) as main_message_id, subject, $select_user 
                                FROM $messages_table
                              WHERE deleted=0 AND $where_user=$user_id $where AND FIND_IN_SET($user_id, $messages_table.deleted_by_users) = 0) x
                    GROUP BY main_message_id) y
                LEFT JOIN $users_table ON $users_table.id= y.$select_user
                LEFT JOIN $messages_table ON $messages_table.id= y.id";

        return $this->db->query($sql);
    }

    function get_chat_list($options = array()) {

        $messages_table = $this->db->prefixTable('messages');
        $users_table = $this->db->prefixTable('users');

        $login_user_id = get_array_value($options, "login_user_id");

        $where = "";
        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND ($messages_table.to_user_id=$user_id OR $messages_table.from_user_id=$user_id) ";
        }

        $user_ids = get_array_value($options, "user_ids");
        if ($user_ids) {
            $where .= " AND ($messages_table.to_user_id IN($user_ids) OR $messages_table.from_user_id IN($user_ids))";
        }

        $this->db->query("SET sql_mode = ''"); //ignor sql mode here

        $sql = "SELECT $messages_table.id, $messages_table.subject, $messages_table.from_user_id, IF(another_m.mex_created_at, another_m.mex_created_at, $messages_table.created_at) AS message_time, 
                IF(another_m.status, another_m.status, $messages_table.status) AS status, (SELECT from_user_id FROM $messages_table WHERE $messages_table.id=another_m.max_id) AS last_from_user_id,
                CONCAT($users_table.first_name, ' ', $users_table.last_name) AS user_name, $users_table.image AS user_image, $users_table.last_online
                FROM `messages`
                LEFT JOIN (SELECT MAX(id) as max_id, MAX(message_id) as mex_message_id, MAX(created_at) as mex_created_at, MAX(status) as status FROM messages WHERE deleted=0 and  message_id!=0 GROUP BY message_id) AS another_m ON $messages_table.id=another_m.mex_message_id
                LEFT JOIN users ON ($users_table.id=$messages_table.from_user_id OR $users_table.id=$messages_table.to_user_id) AND $users_table.id != $login_user_id
                WHERE $messages_table.deleted=0 AND $messages_table.message_id=0 $where AND
                FIND_IN_SET($login_user_id, $messages_table.deleted_by_users) = 0 AND ($messages_table.from_user_id=$login_user_id OR $messages_table.to_user_id=$login_user_id)
                GROUP BY id
                ORDER BY message_time DESC LIMIT 0, 30";

        return $this->db->query($sql);
    }

    /* prepare notifications of new message */

    function get_notifications($user_id, $last_message_checke_at = "0", $active_message_id = 0) {
        $messages_table = $this->db->prefixTable('messages');
        $users_table = $this->db->prefixTable('users');

        $where = "";
        if ($active_message_id) {
            $where = " AND $messages_table.message_id!=$active_message_id";
        }


        $sql = "SELECT $messages_table.id, $messages_table.message_id, $messages_table.created_at, CONCAT($users_table.first_name, ' ', $users_table.last_name) AS user_name, $users_table.image AS user_image
        FROM $messages_table
        LEFT JOIN $users_table ON $users_table.id=$messages_table.from_user_id
        WHERE $messages_table.deleted=0 AND $messages_table.status='unread'  AND $messages_table.to_user_id = $user_id
        AND timestamp($messages_table.created_at)>timestamp('$last_message_checke_at') $where
        ORDER BY timestamp($messages_table.created_at) DESC";
        return $this->db->query($sql);
    }

    /* update message ustats */

    function set_message_status_as_read($message_id, $user_id = 0) {
        $messages_table = $this->db->prefixTable('messages');
        $sql = "UPDATE $messages_table SET status='read' WHERE $messages_table.to_user_id=$user_id AND ($messages_table.message_id=$message_id OR $messages_table.id=$message_id)";
        return $this->db->query($sql);
    }

    function count_unread_message($user_id = 0) {
        $messages_table = $this->db->prefixTable('messages');

        $sql = "SELECT COUNT($messages_table.id) as total
        FROM $messages_table
        WHERE $messages_table.deleted=0 AND $messages_table.status='unread'  AND $messages_table.to_user_id = $user_id";
        return $this->db->query($sql)->getRow()->total;
    }

    function delete_messages_for_user($message_id = 0, $user_id = 0) {
        $messages_table = $this->db->prefixTable('messages');

        $sql = "UPDATE $messages_table SET $messages_table.deleted_by_users = CONCAT($messages_table.deleted_by_users,',',$user_id)
        WHERE $messages_table.id=$message_id OR $messages_table.message_id=$message_id";
        return $this->db->query($sql);
    }

    function clear_deleted_status($message_id = 0) {
        $messages_table = $this->db->prefixTable('messages');

        $sql = "UPDATE $messages_table SET $messages_table.deleted_by_users = ''
        WHERE $messages_table.id=$message_id OR $messages_table.message_id=$message_id";
        return $this->db->query($sql);
    }

    function get_users_for_messaging($options = array()) {
        $users_table = $this->db->prefixTable('users');
        $clients_table = $this->db->prefixTable('clients');

        $where = "";

        $login_user_id = get_array_value($options, "login_user_id");

        $all_members = get_array_value($options, "all_members");
        $specific_members = get_array_value($options, "specific_members");
        $member_to_clients = get_array_value($options, "member_to_clients");
        if ($all_members) {
            if ($member_to_clients) {
                $where .= " AND ($users_table.user_type='staff' OR $users_table.user_type='client')";
            } else {
                $where .= " AND $users_table.user_type='staff'";
            }
        } else if ($specific_members) {
            if (is_array($specific_members) && count($specific_members)) {
                $specific_members = join(",", $specific_members);
            } else {
                $specific_members = '0';
            }

            if ($member_to_clients) {
                $where .= " AND ($users_table.id IN($specific_members) OR $users_table.user_type='client')";
            } else {
                $where .= " AND $users_table.id IN($specific_members)";
            }
        }

        if ($member_to_clients && !$all_members && !$specific_members) {
            $where .= " AND $users_table.user_type='client'";
        }

        $client_to_members = get_array_value($options, "client_to_members");
        $client_id = get_array_value($options, "client_id");
        if ($client_to_members) {
            if ($client_id) {
                $where .= " AND (FIND_IN_SET($users_table.id, '$client_to_members') OR $users_table.client_id=$client_id)";
            } else {
                $where .= " AND FIND_IN_SET($users_table.id, '$client_to_members')";
            }
        }

        if ($client_id && !$client_to_members) {
            $where .= " AND $users_table.client_id=$client_id";
        }

        $sql = "SELECT $users_table.id, $users_table.client_id, $users_table.user_type, $users_table.first_name, $users_table.last_name, $clients_table.company_name,
            $users_table.image,  $users_table.job_title, $users_table.last_online
        FROM $users_table
        LEFT JOIN $clients_table ON $clients_table.id = $users_table.client_id AND $clients_table.deleted=0
        WHERE $users_table.deleted=0 AND $users_table.status='active' AND $users_table.id!=$login_user_id $where
        ORDER BY $users_table.user_type, $users_table.first_name ASC";

        return $this->db->query($sql);
    }

}
