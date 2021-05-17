<?php
$icon = "user";
$title = app_lang("latest_online_team_members");

if ($user_type == "client") {
    $icon = "briefcase";
    $title = app_lang("latest_online_client_contacts");
}
?>

<div class="card bg-white">
    <div class="card-header">
        <i data-feather="<?php echo $icon; ?>" class="icon-16"></i>&nbsp; <?php echo $title; ?>
    </div>
    <div class="card-body active-team-members-list p0 rounded-bottom" id="active-team-members-<?php echo $user_type; ?>">
        <?php
        if ($users) {
            foreach ($users as $user) {
                if ($user->last_online) {
                    $online = "";
                    if ($user->last_online && is_online_user($user->last_online)) {
                        $online = "<i class='online'></i>";
                    } else {
                        $now = get_my_local_time();
                        $last_online = convert_date_utc_to_local($user->last_online);

                        $diff_seconds = abs(strtotime($now) - strtotime($last_online));
                        $diff_minutes = floor($diff_seconds / 60);
                        $diff_hours = floor($diff_minutes / 60);
                        $diff_days = floor($diff_hours / 24);

                        $online = "<span class='text-off'>";

                        if ($diff_minutes < 60) {
                            $online .= $diff_minutes . "m";
                        } else if ($diff_minutes >= 60 && $diff_hours < 24) {
                            $online .= $diff_hours . "h";
                        } else if ($diff_hours >= 24) {
                            $online .= $diff_days . "d";
                        }

                        $online .= "</span>";
                    }
                    ?>
                    <div class="message-row d-flex">
                        <div class="flex-shrink-0">
                            <span class="avatar avatar-xs">
                                <img alt="..." src="<?php echo get_avatar($user->image); ?>">
                            </span>
                        </div>
                        <div class="w-100 ps-2">
                            <div class="mb5 clearfix">
                                <strong class="float-start"> 
                                    <?php
                                    if ($user_type == "staff") {
                                        echo get_team_member_profile_link($user->id, $user->member_name);
                                    } else {
                                        echo get_client_contact_profile_link($user->id, $user->member_name);
                                    }
                                    ?>
                                </strong>
                                <span class="float-end"><?php echo $online; ?></span>
                            </div>
                            <?php
                            $subline = $user->job_title;
                            if ($user->user_type === "client" && $user->company_name) {
                                $subline = $user->company_name;
                            }
                            ?>
                            <small class="text-off block"><?php echo $subline; ?></small>
                        </div>
                    </div>
                    <?php
                }
            }
        }
        ?>
    </div>
</div>

<script>
    $(document).ready(function () {
        initScrollbar('#active-team-members-<?php echo $user_type; ?>', {
            setHeight: 330
        });
    });
</script>