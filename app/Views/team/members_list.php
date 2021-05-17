<div class="modal-body clearfix">
    <div class="container-fluid">
        <?php foreach ($team_members as $member) { ?>

            <div class="form-group">
                <div class="d-flex pb10 b-b">
                    <div class="flex-shrink-0 pl15">
                        <span class="avatar avatar-xs">
                            <img src="<?php echo get_avatar($member->image); ?>" alt="..." />
                        </span>
                    </div>
                    <div class="w-100 ps-2">
                        <div class="mb5 clearfix">
                            <div class="float-start">
                                <?php echo get_team_member_profile_link($member->id, $member->first_name . " " . $member->last_name); ?>
                            </div>
                            <div class="float-end pr15">
                                <label class="badge bg-info text-white"><?php echo $member->job_title; ?></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
</div>
