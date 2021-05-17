<?php

namespace App\Models;

class Stripe_ipn_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'stripe_ipn';
        parent::__construct($this->table);
    }

    function get_one_payment_where($payment_verification_code) {
        $stripe_ipn_table = $this->db->prefixTable('stripe_ipn');

        $sql = "SELECT $stripe_ipn_table.*
        FROM $stripe_ipn_table
        WHERE $stripe_ipn_table.deleted=0 AND $stripe_ipn_table.payment_verification_code='$payment_verification_code'
        ORDER BY $stripe_ipn_table.id DESC
        LIMIT 1";

        return $this->db->query($sql)->getRow();
    }

}
