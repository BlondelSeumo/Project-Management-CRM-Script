<a href="<?php echo get_uri('attendance/index'); ?>" class="white-link">
    <div class="card dashboard-icon-widget">
        <div class="card-body ">
            <div class="widget-icon bg-coral">
                <i data-feather="clock" class='icon'></i>
            </div>
            <div class="widget-details">
                <h1><?php echo $members_clocked_out; ?></h1>
                <span><?php echo app_lang("members_clocked_out"); ?></span>
            </div>
        </div>
    </div>
</a>