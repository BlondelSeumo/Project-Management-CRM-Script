<?php
$card = "";
$total_count = "";
if ($widget_type == "total_hours_worked") {
    $card = "info";
    $total_count = $total_hours_worked;
} else if ($widget_type == "total_project_hours") {
    $card = "primary";
    $total_count = $total_project_hours;
}
?>

<div class="card dashboard-icon-widget">
    <div class="card-body text-white">
        <div class="widget-icon  bg-<?php echo $card; ?>">
            <i data-feather="clock" class="icon"></i>
        </div>
        <div class="widget-details">
            <h1><?php echo $total_count; ?></h1>
            <span><?php echo app_lang($widget_type); ?></span>
        </div>
    </div>
</div>