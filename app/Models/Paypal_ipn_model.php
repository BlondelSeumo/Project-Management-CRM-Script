<?php

namespace App\Models;

class Paypal_ipn_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'paypal_ipn';
        parent::__construct($this->table);
    }

}