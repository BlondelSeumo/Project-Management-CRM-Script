<?php

namespace App\Models;

class Likes_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'likes';
        parent::__construct($this->table);
    }

}
