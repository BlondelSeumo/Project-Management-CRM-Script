<?php

namespace App\Models;

class Invoice_items_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'invoice_items';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $invoice_items_table = $this->db->prefixTable('invoice_items');
        $invoices_table = $this->db->prefixTable('invoices');
        $clients_table = $this->db->prefixTable('clients');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $invoice_items_table.id=$id";
        }
        $invoice_id = get_array_value($options, "invoice_id");
        if ($invoice_id) {
            $where .= " AND $invoice_items_table.invoice_id=$invoice_id";
        }

        $sql = "SELECT $invoice_items_table.*, (SELECT $clients_table.currency_symbol FROM $clients_table WHERE $clients_table.id=$invoices_table.client_id limit 1) AS currency_symbol
        FROM $invoice_items_table
        LEFT JOIN $invoices_table ON $invoices_table.id=$invoice_items_table.invoice_id
        WHERE $invoice_items_table.deleted=0 $where
        ORDER BY $invoice_items_table.sort ASC";
        return $this->db->query($sql);
    }

    function get_item_suggestion($keyword = "", $user_type = "") {
        $items_table = $this->db->prefixTable('items');

        $keyword = $this->db->escapeString($keyword);

        $where = "";
        if ($user_type && $user_type === "client") {
            $where = " AND $items_table.show_in_client_portal=1";
        }

        $sql = "SELECT $items_table.title
        FROM $items_table
        WHERE $items_table.deleted=0  AND $items_table.title LIKE '%$keyword%' $where
        LIMIT 10 
        ";
        return $this->db->query($sql)->getResult();
    }

    function get_item_info_suggestion($item_name = "", $user_type = "") {

        $items_table = $this->db->prefixTable('items');

        $item_name = $this->db->escapeString($item_name);

        $where = "";
        if ($user_type && $user_type === "client") {
            $where = " AND $items_table.show_in_client_portal=1";
        }

        $sql = "SELECT $items_table.*
        FROM $items_table
        WHERE $items_table.deleted=0  AND $items_table.title LIKE '%$item_name%' $where
        ORDER BY id DESC LIMIT 1
        ";

        $result = $this->db->query($sql);

        if ($result->resultID->num_rows) {
            return $result->getRow();
        }
    }

}
