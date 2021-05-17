<?php
$settings_menu = array(
    "app_settings" => array(
        array("name" => "general", "url" => "settings/general"),
        array("name" => "email", "url" => "settings/email"),
        array("name" => "email_templates", "url" => "email_templates"),
        array("name" => "modules", "url" => "settings/modules"),
        array("name" => "cron_job", "url" => "settings/cron_job"),
        array("name" => "notifications", "url" => "settings/notifications"),
        array("name" => "integration", "url" => "settings/integration"),
        array("name" => "updates", "url" => "Updates"),
    ),
    "access_permission" => array(
        array("name" => "roles", "url" => "roles"),
        array("name" => "team", "url" => "team"),
    ),
    "client" => array(
        array("name" => "client_permissions", "url" => "settings/client_permissions"),
        array("name" => "client_groups", "url" => "client_groups"),
        array("name" => "dashboard", "url" => "dashboard/client_default_dashboard"),
        array("name" => "client_left_menu", "url" => "left_menus/index/client_default"),
        array("name" => "client_projects", "url" => "settings/client_projects"),
    ),
    "setup" => array(
        array("name" => "custom_fields", "url" => "custom_fields"),
        array("name" => "tasks", "url" => "task_status"),
    )
);

//restricted settings
if (get_setting("module_attendance") == "1") {
    $settings_menu["access_permission"][] = array("name" => "ip_restriction", "url" => "settings/ip_restriction");
}

if (get_setting("module_event") == "1") {
    $settings_menu["setup"][] = array("name" => "events", "url" => "settings/events");
}

if (get_setting("module_leave") == "1") {
    $settings_menu["setup"][] = array("name" => "leave_types", "url" => "leave_types");
}

if (get_setting("module_ticket") == "1") {
    $settings_menu["setup"][] = array("name" => "tickets", "url" => "ticket_types");
}

if (get_setting("module_expense") == "1") {
    $settings_menu["setup"][] = array("name" => "expense_categories", "url" => "expense_categories");
}

if (get_setting("module_invoice") == "1" || get_setting("module_estimate") == "1") {
    $settings_menu["setup"][] = array("name" => "item_categories", "url" => "item_categories");
}

if (get_setting("module_invoice") == "1") {
    $settings_menu["setup"][] = array("name" => "invoices", "url" => "settings/invoices");
}

if (get_setting("module_estimate") == "1") {
    $settings_menu["setup"][] = array("name" => "estimates", "url" => "settings/estimates");
}

if (get_setting("module_order") == "1") {
    $settings_menu["setup"][] = array("name" => "orders", "url" => "settings/orders");
}

$settings_menu["setup"][] = array("name" => "payment_methods", "url" => "payment_methods");
$settings_menu["setup"][] = array("name" => "company", "url" => "settings/company");
$settings_menu["setup"][] = array("name" => "taxes", "url" => "taxes");

if (get_setting("module_lead") == "1") {
    $settings_menu["setup"][] = array("name" => "leads", "url" => "lead_status");
}

$settings_menu["setup"][] = array("name" => "projects", "url" => "settings/projects");

if (get_setting("module_project_timesheet") == "1") {
    $settings_menu["setup"][] = array("name" => "timesheets", "url" => "settings/timesheets");
}

$settings_menu["setup"][] = array("name" => "gdpr", "url" => "settings/gdpr");
$settings_menu["setup"][] = array("name" => "pages", "url" => "pages");

$settings_menu["setup"][] = array("name" => "left_menu", "url" => "left_menus");

$settings_menu["setup"][] = array("name" => "footer", "url" => "settings/footer");
?>

<ul class="nav nav-tabs vertical settings d-block" role="tablist">
    <?php
    foreach ($settings_menu as $key => $value) {

        //collapse the selected settings tab panel
        $collapse_in = "";
        $collapsed_class = "collapsed";
        if (in_array($active_tab, array_column($value, "name"))) {
            $collapse_in = "show";
            $collapsed_class = "";
        }
        ?>

        <div class="clearfix settings-anchor <?php echo $collapsed_class; ?>" data-bs-toggle="collapse" data-bs-target="#settings-tab-<?php echo $key; ?>">
            <?php echo app_lang($key); ?>
        </div>

        <?php
        echo "<div id='settings-tab-$key' class='collapse $collapse_in'>";
        echo "<ul class='list-group help-catagory'>";

        foreach ($value as $sub_setting) {
            $active_class = "";
            $setting_name = get_array_value($sub_setting, "name");
            $setting_url = get_array_value($sub_setting, "url");

            if ($active_tab == $setting_name) {
                $active_class = "active";
            }

            echo "<a href='" . get_uri($setting_url) . "' class='list-group-item $active_class'>" . app_lang($setting_name) . "</a>";
        }

        echo "</ul>";
        echo "</div>";
    }
    ?>
</ul>