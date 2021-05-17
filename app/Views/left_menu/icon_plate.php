<div class="icon-palet">
    <?php
    $selected_icon = $model_info->icon ? $model_info->icon : "bookmark";
    $icons = array(
        "bookmark",
        "pie-chart",
        "book-open",
        "truck",
        "cloud",
        "folder",
        "book",
        "file",
        "file-text",
        "mail",
        "printer",
        "rss",
        "shopping-cart",
        "tag",
        "upload",
        "bar-chart",
        "coffee",
        "life-buoy",
        "send",
        "rotate-ccw",
        "video",
        "globe",
        "heart",
        "home",
        "briefcase",
        "thumbs-up",
        "settings",
        "check-square",
        "link",
        "menu",
        "camera",
        "message-circle",
        "crosshair",
        "monitor",
        "edit",
        "external-link",
        "flag",
        "hash",
        "key",
        "lock",
        "map",
        "bluetooth",
        "mic",
        "shopping-bag",
        "alert-circle",
        "user",
        "credit-card",
    );

    foreach ($icons as $icon) {
        $active_class = "";
        if ($selected_icon === $icon) {
            $active_class = "active";
        }
        echo "<span class='icon-tag clickable inline-block " . $active_class . "' data-icon='" . $icon . "'><i data-feather='$icon' class='icon-16'></i></span>";
    }
    ?> 
    <input id="icon" type="hidden" name="icon" value="<?php echo $selected_icon; ?>" />
</div>

<script type="text/javascript">
    $(document).ready(function () {

        $(".icon-palet span").click(function () {
            $(".icon-palet").find(".active").removeClass("active");
            $(this).addClass("active");
            $("#icon").val($(this).attr("data-icon"));
        });

    });
</script>