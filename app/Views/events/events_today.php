<a href="<?php echo get_uri('events'); ?>" class="white-link" >
    <div class="card dashboard-icon-widget">
        <div class="card-body">
            <div class="widget-icon bg-success">
                <i data-feather="calendar" class="icon"></i>
            </div>
            <div class="widget-details">
                <h1><?php echo $total; ?></h1>
                <span><?php echo app_lang("events_today"); ?></span>
            </div>
        </div>
    </div>
</a>