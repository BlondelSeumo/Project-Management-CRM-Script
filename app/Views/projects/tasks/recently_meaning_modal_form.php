<?php echo form_open(get_uri("team_members/save_recently_meaning"), array("id" => "recently-meaning-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix p30">
    <div class="container-fluid">
        <div class="form-group">
            <div class="row">
                <label for="recently_meaning" class="col-md-4"><?php echo app_lang('recently_meaning'); ?></label>
                <div class="col-md-8">
                    <?php
                    $recently_meaning = get_setting("user_" . $login_user->id . "_recently_meaning");
                    echo form_dropdown("recently_meaning", $recently_meaning_dropdown, $recently_meaning ? $recently_meaning : "1_days", "class='select2'");
                    ?>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#recently-meaning-form").appForm();
        $("#recently-meaning-form .select2").select2();

        $('#ajaxModal').on('hidden.bs.modal', function () {
            location.reload();
        });
    });
</script>    