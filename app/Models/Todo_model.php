<?php

namespace App\Models;

class Todo_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'to_do';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $todo_table = $this->db->prefixTable('to_do');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $todo_table.id=$id";
        }


        $created_by = get_array_value($options, "created_by");
        if ($created_by) {
            $where .= " AND $todo_table.created_by=$created_by";
        }


        $status = get_array_value($options, "status");
        if ($status) {
            $where .= " AND FIND_IN_SET($todo_table.status,'$status')";
        }

        $select_labels_data_query = $this->get_labels_data_query();

        $sql = "SELECT $todo_table.*, $select_labels_data_query
        FROM $todo_table
        WHERE $todo_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_label_suggestions($user_id) {
        $todo_table = $this->db->prefixTable('to_do');
        $sql = "SELECT GROUP_CONCAT(labels) as label_groups
        FROM $todo_table
        WHERE $todo_table.deleted=0 AND $todo_table.created_by=$user_id";
        return $this->db->query($sql)->getRow()->label_groups;
    }

    function get_search_suggestion($search = "", $created_by = 0) {
        $todo_table = $this->db->prefixTable('to_do');

        $search = $this->db->escapeString($search);

        $sql = "SELECT $todo_table.id, $todo_table.title
        FROM $todo_table  
        WHERE $todo_table.deleted=0 AND $todo_table.created_by=$created_by AND $todo_table.title LIKE '%$search%'
        ORDER BY $todo_table.title ASC
        LIMIT 0, 10";

        return $this->db->query($sql);
    }

}
