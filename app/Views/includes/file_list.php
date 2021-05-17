<?php

if (isset($files) && $files) {
    $files = unserialize($files);
    if (count($files)) {
        $image_only = isset($image_only) ? true : false;
        $timeline_file_path = get_setting("timeline_file_path");

        foreach ($files as $file) {
            $file_name = get_array_value($file, "file_name");

            if ($image_only && is_viewable_image_file($file_name)) {
                $thumbnail = get_source_url_of_file($file, $timeline_file_path, "thumbnail");
                echo "<div class='col-md-2 col-sm-6 pr0 saved-file-item-container'><div style='background-image: url($thumbnail)' class='edit-image-file mb15' ><a href='#' class='delete-saved-file' data-file_name='$file_name'><span data-feather='x' class='icon-16'></span></a></div></div>";
            } else {
                echo "<div class='box saved-file-item-container'><div class='box-content w80p pt5 pb5'>" . remove_file_prefix($file_name) . "</div> <div class='box-content w20p text-right'><a href='#' class='delete-saved-file p5 dark' data-file_name='$file_name'><span data-feather='x' class='icon-16'></span></a></div> </div>";
            }
        }
    }
}
