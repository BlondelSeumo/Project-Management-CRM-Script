<?php
$url = "attendance/save_note";

if ($clock_out == "1") {
    $url = "attendance/log_time/$user_id";
}

echo form_open(get_uri($url), array("id" => "attendance-note-form", "class" => "general-form", "role" => "form"));
?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="form-group">
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
        <input name="clock_out" type="hidden" value="<?php echo $clock_out; ?>" />
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#attendance-note-form").appForm({
            onSuccess: function (result) {
                if (result.clock_widget) {
                    $("#timecard-clock-out").closest("#js-clock-in-out").html(result.clock_widget);
                } else {
                    if (result.isUpdate) {
                        $(".dataTable:visible").appTable({newData: result.data, dataId: result.id});
                    } else {
                        $(".dataTable:visible").appTable({reload: true});
                    }
                }
            }
        });

        setTimeout(function () {
            $("#note").focus();
        }, 200);
    });
</script>