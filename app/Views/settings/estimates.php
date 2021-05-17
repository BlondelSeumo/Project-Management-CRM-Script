<div id="page-content" class="page-wrapper clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "estimates";
            echo view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <?php echo form_open(get_uri("settings/save_estimate_settings"), array("id" => "estimate-settings-form", "class" => "general-form dashed-row", "role" => "form")); ?>
            <div class="card">

                <ul data-bs-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
                    <li><a  role="presentation"  href="javascript:;" data-bs-target="#estimate-settings-tab"> <?php echo app_lang('estimate_settings'); ?></a></li>
                    <li><a role="presentation" href="<?php echo_uri("settings/estimate_request_settings/"); ?>" data-bs-target="#estimate-request-settings-tab"><?php echo app_lang('estimate_request_settings'); ?></a></li>
                </ul>

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade" id="estimate-settings-tab">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <label for="logo" class=" col-md-2"><?php echo app_lang('estimate_logo'); ?> (300x100) </label>
                                    <div class=" col-md-10">
                                        <div class="float-start mr15">
                                            <?php
                                            $estimate_logo = "estimate_logo";
                                            if (!get_setting($estimate_logo)) {
                                                $estimate_logo = "invoice_logo";
                                            }
                                            ?>
                                            <img id="estimate-logo-preview" src="<?php echo get_file_from_setting($estimate_logo); ?>" alt="..." />
                                        </div>
                                        <div class="float-start file-upload btn btn-default btn-sm">
                                            <i data-feather='upload' class="icon-14"></i> <?php echo app_lang("upload_and_crop"); ?>
                                            <input id="estimate_logo_file" class="cropbox-upload upload" name="estimate_logo_file" type="file" data-height="100" data-width="300" data-preview-container="#estimate-logo-preview" data-input-field="#estimate_logo" />
                                        </div>
                                        <div class="mt10 ml10 float-start">
                                            <?php
                                            echo form_upload(array(
                                                "id" => "estimate_logo_file_upload",
                                                "name" => "estimate_logo_file",
                                                "class" => "no-outline hidden-input-file"
                                            ));
                                            ?>
                                            <label for="estimate_logo_file_upload" class="btn btn-default btn-sm">
                                                <i data-feather='upload' class="icon-14"></i> <?php echo app_lang("upload"); ?>
                                            </label>
                                        </div>
                                        <input type="hidden" id="estimate_logo" name="estimate_logo" value=""  />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label for="estimate_prefix" class=" col-md-2"><?php echo app_lang('estimate_prefix'); ?></label>
                                    <div class=" col-md-10">
                                        <?php
                                        echo form_input(array(
                                            "id" => "estimate_prefix",
                                            "name" => "estimate_prefix",
                                            "value" => get_setting("estimate_prefix"),
                                            "class" => "form-control",
                                            "placeholder" => strtoupper(app_lang("estimate")) . " #"
                                        ));
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label for="estimate_color" class=" col-md-2"><?php echo app_lang('estimate_color'); ?></label>
                                    <div class=" col-md-10">
                                        <?php
                                        echo form_input(array(
                                            "id" => "estimate_color",
                                            "name" => "estimate_color",
                                            "value" => get_setting("estimate_color"),
                                            "class" => "form-control",
                                            "placeholder" => "Ex. #e2e2e2"
                                        ));
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label for="send_estimate_bcc_to" class=" col-md-2"><?php echo app_lang('send_estimate_bcc_to'); ?></label>
                                    <div class=" col-md-10">
                                        <?php
                                        echo form_input(array(
                                            "id" => "send_estimate_bcc_to",
                                            "name" => "send_estimate_bcc_to",
                                            "value" => get_setting("send_estimate_bcc_to"),
                                            "class" => "form-control",
                                            "placeholder" => app_lang("email")
                                        ));
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label for="initial_number_of_the_estimate" class="col-md-2"><?php echo app_lang('initial_number_of_the_estimate'); ?></label>
                                    <div class="col-md-3">
                                        <input type="hidden" id="last_estimate_id" name="last_estimate_id" value="<?php echo $last_id; ?>" />
                                        <?php
                                        echo form_input(array(
                                            "id" => "initial_number_of_the_estimate",
                                            "name" => "initial_number_of_the_estimate",
                                            "type" => "number",
                                            "value" => (get_setting("initial_number_of_the_estimate") > ($last_id + 1)) ? get_setting("initial_number_of_the_estimate") : ($last_id + 1),
                                            "class" => "form-control mini",
                                            "data-rule-greaterThan" => "#last_estimate_id",
                                            "data-msg-greaterThan" => app_lang("the_estimates_id_must_be_larger_then_last_estimate_id")
                                        ));
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label for="estimate_footer" class="col-md-2"><?php echo app_lang('estimate_footer') ?></label>
                                    <div class="col-md-10">
                                        <?php
                                        echo form_textarea(array(
                                            "id" => "estimate_footer",
                                            "name" => "estimate_footer",
                                            "value" => get_setting('estimate_footer'),
                                            "class" => "form-control"
                                        ));
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label for="create_new_projects_automatically_when_estimates_gets_accepted" class="col-md-2"><?php echo app_lang("create_new_projects_automatically_when_estimates_gets_accepted"); ?></label>
                                    <div class="col-md-10">
                                        <?php
                                        echo form_checkbox("create_new_projects_automatically_when_estimates_gets_accepted", "1", get_setting("create_new_projects_automatically_when_estimates_gets_accepted") ? true : false, "id='create_new_projects_automatically_when_estimates_gets_accepted' class='form-check-input'");
                                        ?> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary"><span data-feather='check-circle' class="icon-16"></span> <?php echo app_lang('save'); ?></button>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="estimate-request-settings-tab"></div>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<?php echo view("includes/cropbox"); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#estimate-settings-form").appForm({
            isModal: false,
            beforeAjaxSubmit: function (data) {
                $.each(data, function (index, obj) {
                    if (obj.name === "estimate_logo") {
                        var image = replaceAll(":", "~", data[index]["value"]);
                        data[index]["value"] = image;
                    }
                    if (obj.name === "estimate_footer") {
                        data[index]["value"] = encodeAjaxPostData(getWYSIWYGEditorHTML("#estimate_footer"));
                    }
                });
            },
            onSuccess: function (result) {
                if (result.success) {
                    appAlert.success(result.message, {duration: 10000});
                } else {
                    appAlert.error(result.message);
                }

                if ($("#estimate_logo").val() || result.reload_page) {
                    location.reload();
                }
            }
        });
        $("#estimate-settings-form .select2").select2();

        initWYSIWYGEditor("#estimate_footer", {height: 100});

        $(".cropbox-upload").change(function () {
            showCropBox(this);
        });
    });
</script>