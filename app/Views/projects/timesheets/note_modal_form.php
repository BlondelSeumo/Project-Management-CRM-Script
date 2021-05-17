<?php echo form_open(get_uri("projects/save_timelog_note/"), array("id" => "timesheet-note-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
        <div class="form-group">
            <?php if ($login_user->user_type === "staff") { ?>
                <label for="note" class=" col-md-12"><?php echo app_lang('note'); ?></label>
                <div class=" col-md-12">

                    <?php
                    echo form_textarea(array(
                        "id" => "note",
                        "name" => "note",
                        "class" => "form-control",
                        "placeholder" => app_lang('note'),
                        "value" => $model_info->note,
                        "data-rich-text-editor" => true
                    ));
                    ?>
                </div>
                <?php
            } else {
                //show preview
                echo nl2br($model_info->note);
            }
            ?>      
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <?php if ($login_user->user_type === "staff") { ?>
        <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
    <?php } ?>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#timesheet-note-form").appForm({
            onSuccess: function (result) {
                $(".dataTable:visible").appTable({newData: result.data, dataId: result.id});
            }
        });

        setTimeout(function () {
            $("#note").focus();
        }, 200);
    });
</script>