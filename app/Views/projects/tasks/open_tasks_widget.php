<a href="<?php echo get_uri('projects/all_tasks'); ?>" class="white-link" >
    <div class="card dashboard-icon-widget">
        <div class="card-body">
            <div class="widget-icon bg-info">
                <i data-feather="list" class="icon"></i>
            </div>
            <div class="widget-details">
                <h1><?php echo $total; ?></h1>
                <span><?php echo app_lang("my_open_tasks"); ?></span>
            </div>
        </div>
    </div>
</a>