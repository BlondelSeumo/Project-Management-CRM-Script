<?php

$box_class = "mb15";
$caption_class = "more";
$caption_lang = " " . app_lang('more');
if (isset($is_message_row)) {
    $box_class = "message-images mb5 mt5";
    $caption_class .= " message-more";
    $caption_lang = "";
}

if ($files && count($files)) {

    $timeline_file_path = get_setting("timeline_file_path");
    $total_files = count($files);
    echo "<div class='timeline-images " . $box_class . "'>";
    $file_name = $files[0]['file_name'];
    $more_image = "";
    if ($total_files > 1) {
        $more_count = $total_files - 1;
        $more_image = "<span class='$caption_class'>+" . $more_count . $caption_lang . "</span>";
    }

    $shown_preview_image = false;
    $is_localhost = is_localhost();

    foreach ($files as $file) {
        $file_name = $file['file_name'];
        $file_id = get_array_value($file, "file_id");
        $service_type = get_array_value($file, "service_type");

        $url = get_source_url_of_file($file, $timeline_file_path);
        $thumbnail = get_source_url_of_file($file, $timeline_file_path, "thumbnail");

        $image = "";
        $actual_file_name = remove_file_prefix($file_name);
        if ((is_viewable_video_file($file_name) && !$file_id && $service_type != "google") || (is_viewable_video_file($file_name) && $file_id && $service_type == "google" && !get_setting("disable_google_preview"))) {

            if (!$shown_preview_image) {
                $image = "<img src='" . get_file_uri("assets/images/video_preview.jpg") . "' alt='video'/>$more_image";
                $shown_preview_image = true;
            }
            echo "<a href='$url' data-title='" . $actual_file_name . "' class='mfp-iframe'>$image</a>";
        } else if (is_viewable_image_file($file_name)) {

            if (!$shown_preview_image) {
                $image = "<img src='$thumbnail' alt='$file_name'/>$more_image";
                $shown_preview_image = true;
            }
            $mfp_class = "mfp-image";
            if ($file_id && $service_type == "google" && !get_setting("disable_google_preview")) {
                $mfp_class = "mfp-iframe";
            }
            echo "<a href='$url' class='$mfp_class' data-title='" . $actual_file_name . "'>$image</a>";
        } else {
            if (!$shown_preview_image) {
                $image = "<div class='inline-block'><div class='file-mockup'><i data-feather='" . get_file_icon(strtolower(pathinfo($file_name, PATHINFO_EXTENSION))) . "' width='10rem' height='10rem' class='mt-12'></i></div></div>$more_image";
                $shown_preview_image = true;
            }

            if ($file_id && $service_type == "google" && !get_setting("disable_google_preview")) {
                echo "<a data-viewer='google' href='$url' class='mfp-iframe' data-title='" . $actual_file_name . "'>$image</a>";
            } else {
                if (!$is_localhost && is_google_preview_available($file_name) && !get_setting("disable_google_preview")) {
                    echo "<a data-viewer='google' href='https://drive.google.com/viewerng/viewer?url=$url?pid=explorer&efh=false&a=v&chrome=false&embedded=true' class='mfp-iframe' data-title='" . $actual_file_name . "'>$image</a>";
                } else {
                    $uid = uniqid(rand());
                    echo "<a href='#$uid' class='mfp-inline' data-title='" . $actual_file_name . "'>" . $image . "</a>" . '<div id="' . $uid . '" class="mfp-hide container max-w500 text-center p20 bg-white">' . app_lang("file_preview_is_not_available") . '<div class="text-off">' . $actual_file_name . '</div>' . '</div>';
                }
            }
        }
    }
    echo "</div>";
}        