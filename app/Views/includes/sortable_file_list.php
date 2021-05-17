<?php
if (isset($files) && $files) {
    $files = unserialize($files);
    if (count($files)) {
        $timeline_file_path = get_setting("timeline_file_path");
        echo "<div id='sortable-file-list-container'>";
        foreach ($files as $file) {
            $file_name = get_array_value($file, "file_name");
            $thumbnail = get_source_url_of_file($file, $timeline_file_path, "thumbnail");

            if (is_viewable_image_file($file_name)) {
                $show = "<img class='sortable-file' src='$thumbnail' alt='$file_name'/>";
            } else {
                $show = get_file_icon(strtolower(pathinfo($file_name, PATHINFO_EXTENSION)));
            }
            ?>

            <div class="clearfix sortable-file-row mb15" data-file_name="<?php echo $file_name; ?>">
                <span class="move-icon table-cell"><i data-feather="menu" class="icon-16"></i></span>
                <div class="ml15 mr15"><?php echo $show; ?></div>
            </div>

            <?php
        }

        echo "</div>";
        ?>

        <script>
            $(document).ready(function () {
                //make the files sortable
                var $selector = $("#sortable-file-list-container");
                Sortable.create($selector[0], {
                    animation: 150,
                    chosenClass: "sortable-chosen",
                    ghostClass: "sortable-ghost",
                    onUpdate: function (e) {
                        appLoader.show();

                        //prepare file indexes 
                        var data = "";
                        $.each($selector.find(".sortable-file-row"), function (index, ele) {
                            if (data) {
                                data += ":,:";
                            }

                            data += $(ele).attr("data-file_name");
                        });

                        //update file indexes
                        $.ajax({
                            url: '<?php echo $action_url; ?>',
                            type: "POST",
                            data: {sort_values: data, id: <?php echo $id; ?>},
                            success: function () {
                                appLoader.hide();
                            }
                        });
                    }
                });
            });
        </script>

        <?php
    }
}
?>
