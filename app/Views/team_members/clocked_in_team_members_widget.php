<div class="card bg-white">
    <div class="card-header">
        <i data-feather="clock" class="icon-16"></i>&nbsp; <?php echo app_lang("clocked_in_team_members"); ?>
    </div>
    <div class="card-body active-team-members-list p0 rounded-bottom" id="clocked-in-team-members-list">
        <?php
        if ($users) {
            foreach ($users as $user) {
                $attendance_in_time = $user->in_time;
                $explode_attendance_in_time = explode(" ", $attendance_in_time);

                $in_time = "<span class='text-off'>" . "<i data-feather='clock' class='icon-16'></i>" . " ";

                if ($explode_attendance_in_time[0] == get_today_date()) {
                    //if the attendance has been started today, then show only time
                    $in_time .= format_to_time($attendance_in_time);
                } else {
                    //if the attendance hasn't been started today, then show only time
                    $in_time .= format_to_relative_time($attendance_in_time);
                }

                $in_time .= "</span>";
                ?>
                <div class="message-row d-flex">
                    <div class="flex-shrink-0">
                        <span class="avatar avatar-xs">
                            <img alt="..." src="<?php echo get_avatar($user->created_by_avatar); ?>">
                        </span>
                    </div>
                    <div class="w-100 ps-2">
                        <div class="mb5 clearfix">
                            <strong class="float-start"> 
                                <?php echo get_team_member_profile_link($user->user_id, $user->created_by_user); ?>
                            </strong>
                            <span class="float-end"><?php echo $in_time; ?></span>
                        </div>
                        <?php $subline = $user->user_job_title; ?>
                        <small class="text-off block"><?php echo $subline; ?></small>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>
</div>

<script>
    $(document).ready(function () {
        initScrollbar('#clocked-in-team-members-list', {
            setHeight: 330
        });
    });
</script>