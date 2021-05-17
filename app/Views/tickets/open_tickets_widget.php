<div class="card dashboard-icon-widget">
    <div class="card-body">
        <div class="widget-icon bg-success">
            <i data-feather="life-buoy" class="icon"></i>
        </div>
        <div class="widget-details">
            <h1><?php echo $total; ?></h1>
            <span><?php echo anchor(get_uri("tickets"), app_lang("open_tickets"), array("class" => "white-link")); ?></span>
        </div>
    </div>
</div>