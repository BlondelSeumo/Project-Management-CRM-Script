<div class="list-group">
    <?php
    if (count($projects)) {
        foreach ($projects as $project) {

            $icon = "grid";
            if ($project->status == "completed") {
                $icon = "check-circle";
            } else if ($project->status == "canceled") {
                $icon = "x";
            }

            $title = "<i data-feather='$icon' class='icon-16 mr10'></i> " . $project->title;
            echo anchor(get_uri("projects/view/" . $project->id), $title, array("class" => "dropdown-item"));
        }
    } else {
        ?>
        <div class='list-group-item'>
            <?php echo app_lang("empty_starred_projects"); ?>              
        </div>
    <?php } ?>
</div>