<div class="card bg-white">
    <div class="card-header">
        <i data-feather="user" class="icon-16"></i>&nbsp; <?php echo app_lang("active_members_on_projects"); ?>
    </div>
    <div class="card-body active-team-members-list p0 rounded-bottom" id="active_members_on_projects">
        <?php
        foreach ($users_info as $user_info) {
            ?>
            <div class = "message-row d-flex">
                <div class = "flex-shrink-0">
                    <span class = "avatar avatar-xs">
                        <img alt = "..." src = "<?php echo get_avatar($user_info->image); ?>">
                    </span>
                </div>

                <div class="w-100 ps-2">
                    <div class="mb5 clearfix">
                        <strong class="float-start">
                            <?php
                            echo get_team_member_profile_link($user_info->id, $user_info->member_name)
                            ?>
                        </strong>
                    </div>
                    <div>
                        <?php
                        $project_list = explode(",", $user_info->projects_list);
                        foreach ($project_list as $project) {
                            $project_timer = explode("--::--", $project);
                            $in_time = "<span class='text-off'>" . "<i data-feather='clock' class='icon-16'></i>" . " ";
                            $in_time .= format_to_relative_time($project_timer[2]);
                            $in_time .= "</span>";
                            echo "<div class='clearfix row'>";
                            echo "<div class='col-md-7 col-sm-7'>";
                            echo "<small class='text-off block float-start'>" . anchor(get_uri("projects/view/" . $project_timer[0]), $project_timer[1]) . "</small>";
                            echo "</div>";
                            echo "<div  class='col-md-5 col-sm-5'>";
                            echo "<span class='float-end'>" . $in_time . "</span>";
                            echo "</div>";
                            echo "</div>";
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>

    </div>
</div>

<script>
    $(document).ready(function () {
        initScrollbar('#active_members_on_projects', {
            setHeight: 330
        });
    });
</script>