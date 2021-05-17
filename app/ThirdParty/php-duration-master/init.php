<?php

require(dirname(__FILE__) . '/Duration.php');

use Khill\Duration\Duration;

class Init_duration {

    public static function init($time_string = "") {
        return new Duration($time_string);
    }

}
