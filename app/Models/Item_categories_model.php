<?php

namespace App\Models;

class Item_categories_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'item_categories';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $item_categories_table = $this->db->prefixTable('item_categories');
        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $item_categories_table.id=$id";
        }

        $sql = "SELECT $item_categories_table.*
        FROM $item_categories_table
        WHERE $item_categories_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
