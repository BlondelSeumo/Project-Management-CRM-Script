<div id="page-content" class="page-wrapper clearfix">
    <div class="view-container">
        <div class="pt10 pb10 text-right"> &larr; <?php echo anchor("announcements", app_lang("announcements")); ?></div>
        <div class="card no-border">
            <div class="card-body p30">
                <h1 class="mt0 text-default">
                    <?php echo $announcement->title; ?>
                </h1>
                <div class="text-off mb15">
                    <?php echo format_to_date($announcement->start_date); ?>,&nbsp; 
                    <?php echo get_team_member_profile_link($announcement->created_by, $announcement->created_by_user); ?>
                </div>
                <div>
                    <?php
                    echo $announcement->description;
                    ?>
                </div>
                <div class="mt20">
                    <?php
                    if ($announcement->files) {
                        $files = unserialize($announcement->files);
                        $total_files = count($files);
                        echo view("includes/timeline_preview", array("files" => $files));

                        if ($total_files) {
                            $download_caption = app_lang('download');
                            if ($total_files > 1) {
                                $download_caption = sprintf(app_lang('download_files'), $total_files);
                            }


                            echo anchor(get_uri("announcements/download_announcement_files/" . $announcement->id), $download_caption, array("class" => "", "title" => $download_caption));
                        }
                    }
                    ?>
                </div>

            </div>
        </div>
    </div>

</div>