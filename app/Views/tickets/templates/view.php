<div class="modal-body clearfix general-form">
    <div class="container-fluid">
        <div class="form-group">
            <div class="col-md-12 notepad-title">
                <strong><?php echo $model_info->title; ?></strong>
                <?php if ($model_info->private) { ?>
                    <div class='text-off font-11'>
                        <i data-feather="lock" class="icon-16 text-off mr5"></i> <?php echo app_lang("private_template"); ?>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="col-md-12 ">
            <?php echo $model_info->description; ?>
        </div>
    </div>
</div>

<div class="modal-footer">
    <?php
    if ($model_info->created_by == $login_user->id || $login_user->is_admin) {
        echo modal_anchor(get_uri("tickets/ticket_template_modal_form/"), "<i data-feather='edit-2' class='icon-16'></i> " . app_lang('edit'), array("class" => "btn btn-default", "data-post-id" => $model_info->id, "title" => app_lang('edit_template')));
    }
    ?>
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
</div>