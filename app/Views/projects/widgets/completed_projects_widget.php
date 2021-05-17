<a href="<?php echo get_uri('projects/all_projects/completed'); ?>" class="white-link">
    <div class="card dashboard-icon-widget">
        <div class="card-body ">
            <div class="widget-icon bg-success">
                <i data-feather="check-circle" class="icon"></i>
            </div>
            <div class="widget-details">
                <h1><?php echo $project_completed; ?></h1>
                <span><?php echo app_lang("projects_completed"); ?></span>
            </div>
        </div>
    </div>
</a>