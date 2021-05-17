<?php echo form_open(get_uri("events/save_google_calendar_settings"), array("id" => "google-calendar-settings-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix post-dropzone">
    <div class="container-fluid">
        <div class="form-group">
            <div class="row">
                <div class="pb5 col-md-12 clearfix text-off">
                    <?php echo app_lang("note") . ": " . app_lang("google_calendar_help_message"); ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="integrate_with_google_calendar" class=" col-md-3"><?php echo app_lang('integrate_with_google_calendar'); ?></label>

                <div class="col-md-9">
                    <?php
                    $user_id = $login_user->id;
                    $integrate_with_google_calendar = get_setting('user_' . $user_id . '_integrate_with_google_calendar');
                    echo form_checkbox("integrate_with_google_calendar", "1", $integrate_with_google_calendar ? true : false, "id='integrate_with_google_calendar' class='form-check-input'");
                    ?> 
                </div>
            </div>
        </div>

        <div class="clearfix integrate-with-google-calendar-details-section <?php echo $integrate_with_google_calendar ? "" : "hide" ?>">

            <div class="form-group">
                <div class="row">
                    <label class=" col-md-12">
                        <?php echo app_lang("get_your_app_credentials_from_here") . " " . anchor("https://console.developers.google.com", "Google API Console", array("target" => "_blank")); ?>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <label for="google_client_id" class=" col-md-3"><?php echo app_lang('google_client_id'); ?></label>
                    <div class=" col-md-9">
                        <?php
                        echo form_input(array(
                            "id" => "google_client_id",
                            "name" => "google_client_id",
                            "value" => get_setting('user_' . $user_id . '_google_client_id'),
                            "class" => "form-control",
                            "placeholder" => app_lang('google_client_id'),
                            "data-rule-required" => true,
                            "data-msg-required" => app_lang("field_required"),
                        ));
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <label for="google_client_secret" class=" col-md-3"><?php echo app_lang('google_client_secret'); ?></label>
                    <div class=" col-md-9">
                        <?php
                        echo form_input(array(
                            "id" => "google_client_secret",
                            "name" => "google_client_secret",
                            "value" => get_setting('user_' . $user_id . '_google_client_secret'),
                            "class" => "form-control",
                            "placeholder" => app_lang('google_client_secret'),
                            "data-rule-required" => true,
                            "data-msg-required" => app_lang("field_required"),
                        ));
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <label for="redirect_uri" class=" col-md-3"><i data-feather="alert-triangle" class="icon-16 text-warning"></i> <?php echo app_lang('remember_to_add_this_url_in_authorized_redirect_uri'); ?></label>
                    <div class=" col-md-9">
                        <?php
                        echo "<pre class='mt5'>" . get_uri("google_api/save_access_token_of_calendar") . "</pre>"
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group mb15">
                <div class="row">
                    <label for="email" class=" col-md-3"><?php echo app_lang('your_calendar_ids'); ?></label>
                    <div class="col-md-9">
                        <div class="mb5"><i data-feather="alert-triangle" class="icon-16 text-warning"></i> <?php echo app_lang("calendar_ids_help_message"); ?></div>
                        <div class="calendar-ids-field">

                            <?php
                            //show existing calendar ids
                            if (count($calendar_ids)) {
                                foreach ($calendar_ids as $calendar_id) {
                                    ?>

                                    <div class="calendar-ids-form clearfix pb10">
                                        <div class="row">
                                            <div class="col-md-10 p0">
                                                <?php
                                                echo form_input(array(
                                                    "id" => "calendar_id",
                                                    "name" => "calendar_id[]",
                                                    "class" => "form-control",
                                                    "placeholder" => app_lang('calendar_id'),
                                                    "value" => $calendar_id
                                                ));
                                                ?>
                                            </div>   
                                            <div class="col-md-2">
                                                <?php echo js_anchor("<i data-feather='x' class='icon-16'></i> ", array("class" => "remove-calendar-id delete-post-dropzone ml10")); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>

                            <div class="calendar-ids-form clearfix pb10">
                                <div class="row">
                                    <div class="col-md-10 p0">
                                        <?php
                                        echo form_input(array(
                                            "id" => "calendar_id",
                                            "name" => "calendar_id[]",
                                            "class" => "form-control",
                                            "placeholder" => app_lang('calendar_id')
                                        ));
                                        ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?php echo js_anchor("<i data-feather='x' class='icon-16'></i> ", array("class" => "remove-calendar-id delete-post-dropzone ml10")); ?>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <?php echo js_anchor("<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_more'), array("class" => "add-calendar-id", "id" => "add-more-calendar-id")); ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <label for="status" class=" col-md-3"><?php echo app_lang('status'); ?></label>
                    <div class=" col-md-9">
                        <?php if (get_setting('user_' . $user_id . '_google_calendar_authorized')) { ?>
                            <span class="ml5 badge bg-success"><?php echo app_lang("authorized"); ?></span>
                        <?php } else { ?>
                            <span class="ml5 badge" style="background:#F9A52D;"><?php echo app_lang("unauthorized"); ?></span>
                        <?php } ?>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button id="save-button" type="submit" class="btn btn-primary <?php echo $integrate_with_google_calendar ? "hide" : "" ?>"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
    <button id="save-and-authorize-button" type="submit" class="btn btn-primary ml5 <?php echo $integrate_with_google_calendar ? "" : "hide" ?>"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save_and_authorize'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        var $saveAndAuthorizeBtn = $("#save-and-authorize-button"),
                $saveBtn = $("#save-button"),
                $calendarDetailsArea = $(".integrate-with-google-calendar-details-section");

        $("#google-calendar-settings-form").appForm({
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});

                //if google clandar is enabled, redirect to authorization system
                if ($saveBtn.hasClass("hide")) {
                    window.location.href = "<?php echo_uri('google_api/authorize_calendar'); ?>";
                }
            }
        });

        //show/hide google calendar details area
        $("#integrate_with_google_calendar").click(function () {
            if ($(this).is(":checked")) {
                $saveAndAuthorizeBtn.removeClass("hide");
                $saveBtn.addClass("hide");
                $calendarDetailsArea.removeClass("hide");
            } else {
                $saveAndAuthorizeBtn.addClass("hide");
                $saveBtn.removeClass("hide");
                $calendarDetailsArea.addClass("hide");
            }
        });

        var $wrapper = $('.calendar-ids-field'),
                $field = $('.calendar-ids-form:first-child', $wrapper).clone(); //keep a clone for future use.

        //add new field
        $(".add-calendar-id").click(function () {
            var $newField = $field.clone();
            var $newObj = $newField.appendTo($wrapper);
            $newObj.find("input").focus();
            $newObj.find("input").val("");
        });

        //remove calendar id input field
        $('body').on('click', '.remove-calendar-id', function () {
            $(this).closest(".calendar-ids-form").remove();
        });

<?php if (!count($calendar_ids)) { ?>
            $(".remove-calendar-id").hide();
<?php } ?>

    });
</script>