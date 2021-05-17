<?php

namespace App\Controllers;

class Forbidden extends App_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $view_data["heading"] = "403 Forbidden";
        $view_data["message"] = "You don't have  permission to access this module.";
        if ($this->request->isAJAX()) {
            $view_data["no_css"] = true;
        }
        return $this->template->view("errors/html/error_general", $view_data);
    }

}
