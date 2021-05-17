<?php
$user_id = "";
$enable_web_notification = 0;

if (isset($login_user->id)) {
    $user_id = $login_user->id;
    $enable_web_notification = $login_user->enable_web_notification;
}

$https = 0;
if (substr(base_url(), 0, 5) == "https") {
    $https = 1;
}

$csrf_token_name = "";
$csrf_hash = "";

if (get_setting("csrf_protection")) {
    $csrf_token_name = csrf_token();
    $csrf_hash = csrf_hash();
}

$timepicker_minutes_interval = 5;
$timepicker_interval = get_setting("timepicker_minutes_interval");

if ($timepicker_interval) {
    if ($timepicker_interval <= 0 || $timepicker_interval > 30) {
        $timepicker_minutes_interval = 5;
    } else {
        $timepicker_minutes_interval = $timepicker_interval;
    }
}
?>


<script type="text/javascript">
    AppHelper = {};
    AppHelper.baseUrl = "<?php echo base_url(); ?>";
    AppHelper.assetsDirectory = "<?php echo base_url("assets") . "/"; ?>";
    AppHelper.settings = {};
    AppHelper.settings.firstDayOfWeek = "<?php echo (int) get_setting("first_day_of_week") * 1; ?>" || 0;
    AppHelper.settings.currencySymbol = "<?php echo get_setting("currency_symbol"); ?>";
    AppHelper.settings.currencyPosition = "<?php echo get_setting("currency_position"); ?>" || "left";
    AppHelper.settings.decimalSeparator = "<?php echo get_setting("decimal_separator"); ?>";
    AppHelper.settings.thousandSeparator = "<?php echo get_setting("thousand_separator"); ?>";
    AppHelper.settings.noOfDecimals = ("<?php echo get_setting("no_of_decimals"); ?>" == "0") ? 0 : 2;
    AppHelper.settings.displayLength = "<?php echo get_setting("rows_per_page"); ?>";
    AppHelper.settings.dateFormat = "<?php echo get_setting("date_format"); ?>";
    AppHelper.settings.timeFormat = "<?php echo get_setting("time_format"); ?>";
    AppHelper.settings.scrollbar = "<?php echo get_setting("scrollbar"); ?>";
    AppHelper.settings.enableRichTextEditor = "<?php echo get_setting('enable_rich_text_editor'); ?>";
    AppHelper.settings.notificationSoundVolume = "<?php echo get_setting("user_" . $user_id . "_notification_sound_volume"); ?>";
    AppHelper.settings.disableKeyboardShortcuts = "<?php echo get_setting('user_' . $user_id . '_disable_keyboard_shortcuts'); ?>";
    AppHelper.userId = "<?php echo $user_id; ?>";
    AppHelper.notificationSoundSrc = "<?php echo get_file_uri(get_setting("system_file_path") . "notification.mp3"); ?>";

    //push notification
    AppHelper.settings.enablePushNotification = "<?php echo get_setting("enable_push_notification"); ?>";
    AppHelper.settings.userEnableWebNotification = "<?php echo $enable_web_notification; ?>";
    AppHelper.settings.userDisablePushNotification = "<?php echo get_setting("user_" . $user_id . "_disable_push_notification"); ?>";
    AppHelper.settings.pusherKey = "<?php echo get_setting("pusher_key"); ?>";
    AppHelper.settings.pusherCluster = "<?php echo get_setting("pusher_cluster"); ?>";
    AppHelper.settings.pushNotficationMarkAsReadUrl = "<?php echo get_uri("notifications/set_notification_status_as_read"); ?>";
    AppHelper.https = "<?php echo $https; ?>";

    AppHelper.settings.disableResponsiveDataTableForMobile = "<?php echo get_setting("disable_responsive_datatable_for_mobile") ?>";
    AppHelper.settings.disableResponsiveDataTable = "<?php echo get_setting("disable_responsive_datatable") ?>";

    AppHelper.csrfTokenName = "<?php echo $csrf_token_name; ?>";
    AppHelper.csrfHash = "<?php echo $csrf_hash; ?>";

    AppHelper.settings.defaultThemeColor = "<?php echo get_setting("default_theme_color"); ?>";

    AppHelper.settings.timepickerMinutesInterval = <?php echo $timepicker_minutes_interval; ?>;

    AppHelper.settings.weekends = "<?php echo get_setting("weekends"); ?>";

</script>