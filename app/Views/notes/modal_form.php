<?php echo form_open(get_uri("notes/save"), array("id" => "note-form", "class" => "general-form", "role" => "form")); ?>
<div id="notes-dropzone" class="post-dropzone">
    <div class="modal-body clearfix">
        <div class="container-fluid">
            <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
            <input type="hidden" name="project_id" value="<?php echo $project_id; ?>" />
            <input type="hidden" name="client_id" value="<?php echo $client_id; ?>" />
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
            <div class="form-group">
                <div class="col-md-12">
                    <?php
                    echo form_input(array(
                        "id" => "title",
                        "name" => "title",
                        "value" => $model_info->title,
                        "class" => "form-control notepad-title",
                        "placeholder" => app_lang('title'),
                        "autofocus" => true,
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="notepad">
                        <?php
                        echo form_textarea(array(
                            "id" => "description",
                            "name" => "description",
                            "value" => $model_info->description,
                            "class" => "form-control",
                            "placeholder" => app_lang('description') . "...",
                            "data-rich-text-editor" => true
                        ));
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="notepad">
                        <?php
                        echo form_input(array(
                            "id" => "note_labels",
                            "name" => "labels",
                            "value" => $model_info->labels,
                            "class" => "form-control",
                            "placeholder" => app_lang('labels')
                        ));
                        ?>
                    </div>
                </div>
            </div>

            <?php if ($project_id) { ?>
                <?php if ($model_info->is_public) { ?>
                    <input type="hidden" name="is_public" value="<?php echo $model_info->is_public; ?>" />
                <?php } else { ?>
                    <div class="form-group">
                        <label for="mark_as_public"class=" col-md-12">
                            <?php
                            echo form_checkbox("is_public", "1", false, "id='mark_as_public'  class='float-start form-check-input'");
                            ?>    
                            <span class="float-start ml15"> <?php echo app_lang('mark_as_public'); ?> </span>
                            <span id="mark_as_public_help_message" class="ml10 hide"><i data-feather="alert-triangle" class="icon-16 text-warning"></i> <?php echo app_lang("mark_as_public_help_message"); ?></span>
                        </label>
                    </div>
                <?php } ?>
            <?php } ?>

            <div class="form-group">
                <div class="col-md-12">
                    <?php
                    echo view("includes/file_list", array("files" => $model_info->files));
                    ?>
                </div>
            </div>

            <?php echo view("includes/dropzone_preview"); ?>
        </div>
    </div>

    <div class="modal-footer">
        <button class="btn btn-default upload-file-button float-start me-auto btn-sm round" type="button" style="color:#7988a2"><i data-feather="camera" class="icon-16"></i> <?php echo app_lang("upload_file"); ?></button>
        <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
        <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
    </div>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        var uploadUrl = "<?php echo get_uri("notes/upload_file"); ?>";
        var validationUri = "<?php echo get_uri("notes/validate_notes_file"); ?>";

        var dropzone = attachDropzoneWithForm("#notes-dropzone", uploadUrl, validationUri);

        $("#note-form").appForm({
            onSuccess: function (result) {
                $("#note-table").appTable({newData: result.data, dataId: result.id});
            }
        });

        setTimeout(function () {
            $("#title").focus();
        }, 200);

        $("#note_labels").select2({multiple: true, data: <?php echo json_encode($label_suggestions); ?>});

        //show/hide mark as public help message
        $("#mark_as_public").click(function () {
            if ($(this).is(":checked")) {
                $("#mark_as_public_help_message").removeClass("hide");
            } else {
                $("#mark_as_public_help_message").addClass("hide");
            }
        });
    });
</script>    