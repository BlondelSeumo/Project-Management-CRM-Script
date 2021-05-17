<style type="text/css">
    .post-file-previews {border:none !important; }
    .client-info-section  .form-group {margin: 25px 15px;}
    #page-content.page-wrapper{padding: 10px !important}
    #content{margin-top: 15px !important}
</style>

<div id="page-content" class="page-wrapper clearfix">
    <div id="external-ticket-form-container">

        <?php echo form_open(get_uri("external_tickets/save"), array("id" => "ticket-form", "class" => "general-form", "role" => "form")); ?>
        <div id="new-ticket-dropzone" class="card p15 no-border clearfix post-dropzone client-info-section" style="max-width: 1000px; margin: auto;">

            <h3 class=" pl15 pr10 pb20 b-b"> <?php echo app_lang("submit_your_request"); ?></h3>

            <div class="form-group">
                <label for="title"><?php echo app_lang('title'); ?></label>
                <div>
                    <?php
                    echo form_input(array(
                        "id" => "title",
                        "name" => "title",
                        "value" => "",
                        "class" => "form-control",
                        "placeholder" => app_lang('title'),
                        "autofocus" => true,
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label for="ticket_type_id"><?php echo app_lang('ticket_type'); ?></label>
                <div>
                    <?php
                    echo form_dropdown("ticket_type_id", $ticket_types_dropdown, "", "class='select2'");
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label for="description"><?php echo app_lang('description'); ?></label>
                <div>
                    <?php
                    echo form_textarea(array(
                        "id" => "description",
                        "name" => "description",
                        "class" => "form-control",
                        "placeholder" => app_lang('description'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                        "data-rich-text-editor" => true
                    ));
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label for="email"><?php echo app_lang('your_email'); ?></label>
                <div>
                    <?php
                    echo form_input(array(
                        "id" => "email",
                        "name" => "email",
                        "class" => "form-control p10",
                        "autofocus" => true,
                        "placeholder" => app_lang('email'),
                        "data-rule-email" => true,
                        "data-msg-email" => app_lang("enter_valid_email"),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label for="name"><?php echo app_lang('your_name'); ?></label>
                <div>
                    <?php
                    echo form_input(array(
                        "id" => "name",
                        "name" => "name",
                        "value" => "",
                        "class" => "form-control",
                        "placeholder" => app_lang('name'),
                    ));
                    ?>
                </div>
            </div>

            <div>
                <?php echo view("signin/re_captcha"); ?>
            </div>

            <div class="clearfix pl10 pr10 b-b">
                <?php echo view("includes/dropzone_preview"); ?>    
            </div>

            <div class="p15">
                <button class="btn btn-default upload-file-button mr15 round" type="button" style="color:#7988a2"><i data-feather="camera" class="icon-16"></i> <?php echo app_lang("upload_file"); ?></button>
                <button type="submit" class="btn btn-primary"><span data-feather="send" class="icon-16"></span> <?php echo app_lang('submit'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?>

    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var uploadUrl = "<?php echo get_uri("external_tickets/upload_file"); ?>";
        var validationUrl = "<?php echo get_uri("external_tickets/validate_file"); ?>";
        var dropzone = attachDropzoneWithForm("#new-ticket-dropzone", uploadUrl, validationUrl);

        $("#ticket-form").appForm({
            isModal: false,
            onSubmit: function () {
                appLoader.show();
                $("#ticket-form").find('[type="submit"]').attr('disabled', 'disabled');
            },
            onSuccess: function (result) {
                appLoader.hide();
                $("#external-ticket-form-container").html("");
                appAlert.success(result.message, {container: "#external-ticket-form-container", animate: false});
                $('.scrollable-page').scrollTop(0); //scroll to top
            }
        });

        setTimeout(function () {
            $("#title").focus();
        }, 200);

        $("#ticket-form .select2").select2();
    });
</script>