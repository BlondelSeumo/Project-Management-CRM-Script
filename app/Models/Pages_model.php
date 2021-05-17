<?php

namespace App\Models;

class Pages_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'pages';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $pages_table = $this->db->prefixTable('pages');

        $where = "";

        $id = get_array_value($options, "id");
        if ($id) {
            $where = " AND $pages_table.id=$id";
        }



        $slug = get_array_value($options, "slug");

        if ($slug) {
            $slug = $this->db->escapeString($slug);
            $where = " AND $pages_table.slug='$slug'";
        }

        $sql = "SELECT $pages_table.*
        FROM $pages_table
        WHERE $pages_table.deleted=0 $where";
        return $this->db->query($sql);
    }

    function is_slug_exists($slug, $id = 0) {
        $result = $this->get_all_where(array("slug" => $slug, "deleted" => 0));
        if (count($result->getResult()) && $result->getRow()->id != $id) {
            return $result->getRow();
        } else {
            return false;
        }
    }

}
