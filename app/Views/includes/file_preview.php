<?php
if (!get_setting("disable_google_preview") && ($is_google_drive_file || (!is_localhost() && $is_google_preview_available))) {
    //don't show google preview in localhost
    $src_url = "https://drive.google.com/viewerng/viewer?url=$file_url?pid=explorer&efh=false&a=v&chrome=false&embedded=true";
    if ($is_google_drive_file) {
        $src_url = $file_url;
    }
    ?>

    <iframe id='google-file-viewer' src="<?php echo $src_url; ?>" style="width: 100%; margin: 0; border: 0;"></iframe>

    <script type="text/javascript">
        $(document).ready(function () {
            $("#google-file-viewer").css({height: $(window).height() + "px"});
            $(".app-modal .expand").hide();
        });
    </script>
    <?php
} else if ($is_image_file) {
    ?>
    <img src="<?php echo $file_url; ?>" />
    <?php
} else if ($is_viewable_video_file && !$is_google_drive_file) {
    //show with default iframe
    ?>

    <iframe id="iframe-file-viewer" src="<?php echo $file_url ?>" style="width: 100%; margin: 0; border: 0; height: 100%;"></iframe>

    <script type="text/javascript">
        $(document).ready(function () {
            $("#iframe-file-viewer").closest("div.app-modal-content-area").css({"height": "100%", display: "table", width: "100%"});
        });
    </script>
    <?php
} else {
    //Preview is not avaialble. 
    echo app_lang("file_preview_is_not_available") . "<br />";
    echo anchor($file_url, app_lang("download"));
}
?>

<script>
    function initScrollbarOnCommentContainer() {
        if ($("#file-preview-comment-container").height() > ($(window).height() - 300)) {
            initScrollbar('#file-preview-comment-container', {
                setHeight: $(window).height() - 300
            });
        }
    }
</script>