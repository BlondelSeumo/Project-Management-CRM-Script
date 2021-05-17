<div class="card bg-white">
    <div class="card-header">
        <i data-feather="grid" class="icon-16"></i>&nbsp; <?php echo app_lang("open_projects"); ?>
    </div>
    <div class="card-body pt0 rounded-bottom" id="open-projects-container">
        <?php
        if ($projects) {
            foreach ($projects as $project) {

                echo "<div class='clearfix row projects-row'>";

                echo "<div class='col-md-7 col-sm-7 mt15'>" . anchor(get_uri("projects/view/" . $project->id), $project->title) . "</div>";

                $progress = $project->total_points ? round(($project->completed_points / $project->total_points) * 100) : 0;

                $class = "bg-primary";
                if ($progress == 100) {
                    $class = "progress-bar-success";
                }

                echo "<div class='col-md-5 col-sm-5'>
                                <div class='progress' title='$progress%'>
                                    <div  class='progress-bar $class' role='progressbar' aria-valuenow='$progress' aria-valuemin='0' aria-valuemax='100' style='width: $progress%'></div>
                                </div>
                            </div>";

                echo "</div>";
            }
        }
        ?>

    </div>
</div>

<script>
    $(document).ready(function () {
        initScrollbar('#open-projects-container', {
            setHeight: 330
        });
    });
</script>