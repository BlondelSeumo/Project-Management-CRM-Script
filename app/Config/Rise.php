<?php

/* Don't change or add any new config in this file */

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Rise extends BaseConfig {

    public $app_settings_array = array(
        "app_version" => "2.7.1",
        "app_update_url" => 'https://releases.fairsketch.com/rise/',
        "updates_path" => './updates/',
    );
    public $app_csrf_exclude_uris = array(
        "notification_processor/create_notification",
        "paypal_ipn", "paypal_ipn/index",
        "paytm_redirect", "paytm_redirect/index", "paytm_redirect.*+",
        "stripe_redirect", "stripe_redirect/index",
        "pay_invoice", "pay_invoice/index",
        "google_api/save_access_token", "google_api/save_access_token_of_calendar",
        "webhooks_listener.*+",
        "external_tickets.*+",
        "cron"
    );

}
