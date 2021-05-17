<?php

namespace App\Models;

class Order_items_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'order_items';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $order_items_table = $this->db->prefixTable('order_items');
        $items_table = $this->db->prefixTable('items');
        $clients_table = $this->db->prefixTable('clients');
        $users_table = $this->db->prefixTable('users');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $order_items_table.id=$id";
        }

        $created_by = get_array_value($options, "created_by");
        if ($created_by) {
            $where .= " AND $order_items_table.created_by=$created_by";
        }

        $order_id = get_array_value($options, "order_id");
        if ($order_id) {
            $where .= " AND $order_items_table.order_id=$order_id";
        }

        $processing = get_array_value($options, "processing");
        if ($processing && $created_by) {
            $where .= " AND $order_items_table.order_id=0";
        }

        $sql = "SELECT $order_items_table.*, $items_table.files,
            (SELECT $clients_table.currency_symbol FROM $clients_table WHERE $clients_table.id=(SELECT $users_table.client_id FROM $users_table WHERE $users_table.id=$order_items_table.created_by) limit 1) AS currency_symbol
        FROM $order_items_table
        LEFT JOIN $items_table ON $items_table.id=$order_items_table.item_id
        WHERE $order_items_table.deleted=0 $where
        ORDER BY $order_items_table.id ASC";
        return $this->db->query($sql);
    }

}
