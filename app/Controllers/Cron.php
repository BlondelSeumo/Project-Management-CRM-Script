<?php

namespace App\Controllers;

use App\Libraries\Cron_job;

class Cron extends App_Controller {

    private $cron_job;

    function __construct() {
        parent::__construct();
        $this->cron_job = new Cron_job();
    }

    function index() {
        ini_set('max_execution_time', 300); //execute maximum 300 seconds 
        //wait at least 5 minute befor starting new cron job
        $last_cron_job_time = get_setting('last_cron_job_time');

        $current_time = strtotime(get_current_utc_time());

        if ($last_cron_job_time == "" || ($current_time > ($last_cron_job_time*1 + 300))) {
            $this->cron_job->run();
            $this->Settings_model->save_setting("last_cron_job_time", $current_time);
        }
    }

}

/* End of file Cron.php */
/* Location: ./app/controllers/Cron.php */