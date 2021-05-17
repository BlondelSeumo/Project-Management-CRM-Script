<a href="<?php echo get_uri('attendance/index/members_clocked_in'); ?>" class="white-link">
    <div class="card dashboard-icon-widget">
        <div class="card-body">
            <div class="widget-icon bg-info">
                <i data-feather="clock" class='icon'></i>
            </div>
            <div class="widget-details">
                <h1><?php echo $members_clocked_in; ?></h1>
                <span><?php echo app_lang("members_clocked_in"); ?></span>
            </div>
        </div>
    </div>
</a>