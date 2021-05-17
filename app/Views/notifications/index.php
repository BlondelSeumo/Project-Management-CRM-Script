<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4> <?php echo app_lang('notifications'); ?></h4>
        </div>
        <div>
            <?php
            $view_data["notifications"] = $notifications;

            echo view("notifications/list_data", $view_data);
            ?>
        </div>
    </div>
</div>
