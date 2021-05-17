<?php

namespace App\Models;

class Labels_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'labels';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $labels_table = $this->db->prefixTable('labels');

        $where = "";

        $context = get_array_value($options, "context");
        if ($context) {
            $where .= " AND $labels_table.context='$context'";
        }

        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $labels_table.user_id=$user_id";
        }

        $label_ids = get_array_value($options, "label_ids");
        if ($label_ids) {
            $where .= " OR $labels_table.id IN($label_ids)";
        }

        $sql = "SELECT $labels_table.*
        FROM $labels_table
        WHERE $labels_table.deleted=0 $where 
        ORDER BY $labels_table.id DESC";

        return $this->db->query($sql);
    }

    function label_group_list($label_ids = "") {
        if (preg_match('/[A-Za-z]/', $label_ids)) {
            //strings found, prepare class object with values
            $result = new \stdClass();
            $result->label_group_name = $label_ids;
            return $result;
        } else {
            $labels_table = $this->db->prefixTable('labels');

            $sql = "SELECT GROUP_CONCAT(' ', $labels_table.title) AS label_group_name
            FROM $labels_table
            WHERE FIND_IN_SET($labels_table.id, '$label_ids')";
            return $this->db->query($sql)->getRow();
        }
    }

    function is_label_exists($id = 0, $type = "") {
        if ($id && $type) {
            $table = $this->db->prefixTable($type);

            $sql = "SELECT COUNT($table.id) AS existing_labels FROM $table WHERE $table.deleted=0 AND $table.labels REGEXP '[[:<:]]" . $id . "[[:>:]]'";

            return $this->db->query($sql)->getRow()->existing_labels;
        }
    }

}
