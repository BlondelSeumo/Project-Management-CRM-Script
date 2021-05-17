<div class="card bg-white">
    <div class="card-header">
        <i data-feather="user" class="icon-16"></i>&nbsp; <?php echo app_lang("all_team_members"); ?>
    </div>
    <div class="card-body rounded-bottom" id="all-team-members-list">
        <?php
        if ($members) {
            foreach ($members as $member) {
                $image_url = get_avatar($member->image);
                $avatar = "<span data-bs-toggle='tooltip' title='" . $member->first_name . " " . $member->last_name . "' class='avatar avatar-sm mr10 mb15'><img src='$image_url' alt='...'></span>";

                echo get_team_member_profile_link($member->id, $avatar);
            }
        }
        ?>

    </div>
</div>

<script>
    $(document).ready(function () {
        initScrollbar('#all-team-members-list', {
            setHeight: 330
        });

        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>