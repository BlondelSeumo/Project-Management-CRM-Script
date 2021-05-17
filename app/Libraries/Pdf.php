<?php

namespace App\Libraries;

require_once APPPATH . "ThirdParty/tcpdf/tcpdf.php";

class Pdf extends \TCPDF {

    public function __construct() {
        parent::__construct();
    }

}
