<?php

namespace App\Models;

class Ticket_templates_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'ticket_templates';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $ticket_templates_table = $this->db->prefixTable('ticket_templates');
        $ticket_types_table = $this->db->prefixTable('ticket_types');

        $where = "";

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $ticket_templates_table.id=$id";
        }

        $created_by = get_array_value($options, "created_by");
        if ($created_by) {
            $where_created_by_or_global = " AND ($ticket_templates_table.created_by=$created_by OR $ticket_templates_table.private=0)";
            $where .= $where_created_by_or_global;

            $where_no_type_or_global = "OR ($ticket_templates_table.ticket_type_id=0 $where_created_by_or_global)";

            $ticket_type_id = get_array_value($options, "ticket_type_id");
            if ($ticket_type_id) {
                $where .= " AND ($ticket_templates_table.ticket_type_id=$ticket_type_id $where_no_type_or_global)";
            }

            $allowed_ticket_types = get_array_value($options, "allowed_ticket_types");
            if (is_array($allowed_ticket_types) && count($allowed_ticket_types) && $created_by) {
                $allowed_ticket_types = join(",", $allowed_ticket_types);
                $where .= " AND ($ticket_templates_table.ticket_type_id IN($allowed_ticket_types) $where_no_type_or_global)";
            }
        }

        $sql = "SELECT $ticket_templates_table.*, $ticket_types_table.title AS ticket_type
        FROM $ticket_templates_table
        LEFT JOIN $ticket_types_table ON $ticket_types_table.id= $ticket_templates_table.ticket_type_id
        WHERE $ticket_templates_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
