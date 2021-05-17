<?php

namespace App\Controllers;

/* Please extend your controllers from Security_Controller, instead of Pre_loader.
 * We'll remove this in future update.
 */

class Pre_loader extends Security_Controller {

    function __construct() {
        parent::__construct();
    }

}
