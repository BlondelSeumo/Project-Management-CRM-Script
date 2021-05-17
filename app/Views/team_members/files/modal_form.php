<?php echo form_open(get_uri("team_members/save_file"), array("id" => "file-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
        <?php
        echo view("includes/multi_file_uploader", array(
            "upload_url" => get_uri("team_members/upload_file"),
            "validation_url" => get_uri("team_members/validate_file"),
        ));
        ?>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default cancel-upload" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" disabled="disabled" class="btn btn-primary start-upload"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#file-form").appForm({
            onSuccess: function (result) {
                $("#team-member-file-table").appTable({reload: true});
            }
        });

    });

</script>    
