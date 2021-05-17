<div class="card bg-white">
    <div class="card-header">
        <i data-feather="eye" class="icon-16"></i>&nbsp; <?php echo app_lang("clocked_out_team_members"); ?>
    </div>
    <div class="card-body active-team-members-list p0 rounded-bottom" id="clocked-out-team-members-list">
        <?php
        if ($users) {
            foreach ($users as $user) {
                if ($user->last_online && is_online_user($user->last_online)) {
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
                                    <?php echo get_team_member_profile_link($user->id, $user->member_name); ?>
                                </strong>
                                <span class="float-end"><i class='online'></i></span>
                            </div>
                            <?php $subline = $user->job_title; ?>
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
        initScrollbar('#clocked-out-team-members-list', {
            setHeight: 330
        });
    });
</script>