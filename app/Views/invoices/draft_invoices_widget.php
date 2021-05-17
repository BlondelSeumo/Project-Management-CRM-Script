<a href="<?php echo get_uri('invoices/index'); ?>" class="white-link">
    <div class="card  dashboard-icon-widget">
        <div class="card-body">
            <div class="widget-icon bg-orange">
                <i data-feather="file-text" class="icon"></i>
            </div>
            <div class="widget-details">
                <h1><?php echo $draft_invoices; ?></h1>
                <span><?php echo app_lang("draft_invoices"); ?></span>
            </div>
        </div>
    </div>
</a>