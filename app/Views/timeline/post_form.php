<div id="post-form-container">
    <?php echo form_open(get_uri("timeline/save"), array("id" => "post-form", "class" => "general-form", "role" => "form")); ?>
    <div class="box">
        <div class="box-content avatar avatar-md pr15 d-table-cell">
            <img src="<?php echo get_avatar($login_user->image); ?>" alt="..." />
        </div>
        <div id="post-dropzone" class="post-dropzone box-content form-group">
            <input type="hidden" name="post_id" value="<?php echo isset($post_id) ? $post_id : 0; ?>">
            <input type="hidden" name="reload_list" value="1">
            <?php
            echo form_textarea(array(
                "id" => "post_description",
                "name" => "description",
                "class" => "form-control white",
                "placeholder" => app_lang('post_placeholder_text'),
                "data-rule-required" => true,
                "data-msg-required" => app_lang("field_required"),
                "data-rich-text-editor" => true,
                "style" => "height: 6rem;"
            ));
            ?>

            <?php echo view("includes/dropzone_preview"); ?>

            <footer class="card-footer b-a clearfix">
                <button class="btn btn-default upload-file-button btn-sm round float-start" type="button" style="color:#7988a2"><i data-feather="camera" class='icon-16'></i> <?php echo app_lang("upload_file"); ?></button>
                <button class="submit-button btn btn-primary btn-sm float-end" type="submit"><i data-feather="send" class='icon-16'></i> <?php echo app_lang("post"); ?></button>
            </footer>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {

        var uploadUrl = "<?php echo get_uri("timeline/upload_file"); ?>";
        var validationUrl = "<?php echo get_uri("timeline/validate_post_file"); ?>";
        var dropzone = attachDropzoneWithForm("#post-dropzone", uploadUrl, validationUrl);

        $("#post-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                if ($("body").hasClass("dropzone-disabled")) {
                    location.reload();
                } else {
                    $("#post_description").val("");
                    $("#timeline").prepend(result.data);
                    dropzone.removeAllFiles();
                }
            }
        });

    });
</script>