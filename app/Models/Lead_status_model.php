<?php

namespace App\Models;

class Lead_status_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'lead_status';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $lead_status_table = $this->db->prefixTable('lead_status');
        $clients_table = $this->db->prefixTable('clients');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $lead_status_table.id=$id";
        }

        $sql = "SELECT $lead_status_table.*, (SELECT COUNT($clients_table.id) FROM $clients_table WHERE $clients_table.deleted=0 AND $clients_table.is_lead=1 AND $clients_table.lead_status_id=$lead_status_table.id) AS total_leads
        FROM $lead_status_table
        WHERE $lead_status_table.deleted=0 $where
        ORDER BY $lead_status_table.sort ASC";
        return $this->db->query($sql);
    }

    function get_max_sort_value() {
        $lead_status_table = $this->db->prefixTable('lead_status');

        $sql = "SELECT MAX($lead_status_table.sort) as sort
        FROM $lead_status_table
        WHERE $lead_status_table.deleted=0";
        $result = $this->db->query($sql);
        if ($result->resultID->num_rows) {
            return $result->getRow()->sort;
        } else {
            return 0;
        }
    }

    function get_first_status() {
        $lead_status_table = $this->db->prefixTable('lead_status');

        $sql = "SELECT $lead_status_table.id AS first_lead_status
        FROM $lead_status_table
        WHERE $lead_status_table.deleted=0
        ORDER BY $lead_status_table.sort ASC
        LIMIT 1";

        return $this->db->query($sql)->getRow()->first_lead_status;
    }

}
