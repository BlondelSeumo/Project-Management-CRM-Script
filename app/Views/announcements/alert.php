<?php
foreach ($announcements as $announcement) {
    ?>
    <div id="<?php echo "announcement-$announcement->id"; ?>" class="alert alert-warning alert-dismissible"><i data-feather="volume-2" class="icon-18 mr10"></i> 
        <?php
        echo anchor(get_uri("announcements/view/" . $announcement->id), $announcement->title);
        echo ajax_anchor(get_uri("announcements/mark_as_read/" . $announcement->id), "", array("class" => "btn-close", "data-remove-on-click" => "#announcement-$announcement->id"));
        ?>
    </div>
    <?php
}
?>