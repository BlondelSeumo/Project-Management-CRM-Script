<div class="card bg-white">
    <div class="card-header">
        <?php
        if ($icon) {
            echo "<i data-feather='$icon' class='icon-16'></i>&nbsp; ";
        }
        echo app_lang($widget);
        ?>
    </div>

    <?php
    $js_id = "";
    if ($widget == "project_timeline") {
        $js_id = "project-timeline-container";
    }
    ?>

    <div id="<?php echo $js_id; ?>">
        <div class="card-body"> 
            <?php
            if ($widget == "project_timeline") {
                echo activity_logs_widget(array("log_for" => "project", "limit" => 10));
            }
            ?>
        </div>
    </div>    
</div>