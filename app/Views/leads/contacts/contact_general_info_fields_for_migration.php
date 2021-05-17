<input type="hidden" name="contact_id[]" value="<?php echo $model_info->id; ?>" />
<div class="form-group">
    <div class="row">
        <?php
        $label_column = isset($label_column) ? $label_column : "col-md-3";
        $field_column = isset($field_column) ? $field_column : "col-md-9";
        ?>
        <label for="first_name-<?php echo $model_info->id; ?>" class="<?php echo $label_column; ?>"><?php echo app_lang('first_name'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "first_name-$model_info->id",
                "name" => "first_name-$model_info->id",
                "value" => $model_info->first_name,
                "class" => "form-control",
                "placeholder" => app_lang('first_name'),
                "data-rule-required" => true,
                "data-msg-required" => app_lang("field_required"),
            ));
            ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <label for="last_name-<?php echo $model_info->id; ?>" class="<?php echo $label_column; ?>"><?php echo app_lang('last_name'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "last_name-$model_info->id",
                "name" => "last_name-$model_info->id",
                "value" => $model_info->last_name,
                "class" => "form-control",
                "placeholder" => app_lang('last_name'),
                "data-rule-required" => true,
                "data-msg-required" => app_lang("field_required"),
            ));
            ?>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <label for="email-<?php echo $model_info->id; ?>" class="<?php echo $label_column; ?>"><?php echo app_lang('email'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "email-$model_info->id",
                "name" => "email-$model_info->id",
                "value" => $model_info->email,
                "class" => "form-control",
                "placeholder" => app_lang('email'),
                "data-rule-email" => true,
                "data-msg-email" => app_lang("enter_valid_email"),
                "data-rule-required" => true,
                "data-msg-required" => app_lang("field_required"),
                "autocomplete" => "off"
            ));
            ?>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <label for="phone-<?php echo $model_info->id; ?>" class="<?php echo $label_column; ?>"><?php echo app_lang('phone'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "phone-$model_info->id",
                "name" => "contact_phone-$model_info->id", //there has another phone field on company info
                "value" => $model_info->phone ? $model_info->phone : "",
                "class" => "form-control",
                "placeholder" => app_lang('phone')
            ));
            ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <label for="skype-<?php echo $model_info->id; ?>" class="<?php echo $label_column; ?>">Skype</label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "skype-$model_info->id",
                "name" => "skype-$model_info->id",
                "value" => $model_info->skype ? $model_info->skype : "",
                "class" => "form-control",
                "placeholder" => "Skype"
            ));
            ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <label for="job_title-<?php echo $model_info->id; ?>" class="<?php echo $label_column; ?>"><?php echo app_lang('job_title'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "job_title-$model_info->id",
                "name" => "job_title-$model_info->id",
                "value" => $model_info->job_title,
                "class" => "form-control",
                "placeholder" => app_lang('job_title')
            ));
            ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <label for="gender-<?php echo $model_info->id; ?>" class="<?php echo $label_column; ?>"><?php echo app_lang('gender'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_radio(array(
                "id" => "gender_male-$model_info->id",
                "name" => "gender-$model_info->id",
                "class" => "form-check-input",
                "data-msg-required" => app_lang("field_required"),
                    ), "male", ($model_info->gender == "female") ? false : true);
            ?>
            <label for="gender_male-<?php echo $model_info->id; ?>" class="mr15"><?php echo app_lang('male'); ?></label> <?php
            echo form_radio(array(
                "id" => "gender_female-$model_info->id",
                "name" => "gender-$model_info->id",
                "class" => "form-check-input",
                "data-msg-required" => app_lang("field_required"),
                    ), "female", ($model_info->gender == "female") ? true : false);
            ?>
            <label for="gender_female-<?php echo $model_info->id; ?>" class=""><?php echo app_lang('female'); ?></label>
        </div>
    </div>
</div>

<div class="custom-fields-on-migration" data-user-id="<?php echo $model_info->id; ?>">
    <?php echo view("custom_fields/form/prepare_context_fields", array("custom_fields" => $custom_fields, "label_column" => $label_column, "field_column" => $field_column)); ?> 
</div>

<?php
//show these filds during new contact creation
//also check the client login setting

if (!get_setting("disable_client_login")) {
    ?>
    <div class="form-group">
        <div class="row">
            <label for="login_password-<?php echo $model_info->id; ?>" class="col-md-3"><?php echo app_lang('password'); ?></label>
            <div class=" col-md-8">
                <div class="input-group">
                    <?php
                    echo form_password(array(
                        "id" => "login_password-$model_info->id",
                        "name" => "login_password-$model_info->id",
                        "class" => "form-control",
                        "placeholder" => app_lang('password'),
                        "style" => "z-index:auto;",
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                        "data-rule-minlength" => 6,
                        "data-msg-minlength" => app_lang("enter_minimum_6_characters")
                    ));
                    ?>
                    <label for="password-<?php echo $model_info->id; ?>" class="input-group-text mb0 clickable" id="generate_password-<?php echo $model_info->id; ?>"><span data-feather="key" class="icon-16"></span> <?php echo app_lang('generate'); ?></label>
                </div>
            </div>
            <div class="col-md-1 p0">
                <a href="#" id="show_hide_password-<?php echo $model_info->id; ?>" class="btn btn-default" title="<?php echo app_lang('show_text'); ?>"><span data-feather="eye" class="icon-16"></span></a>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($login_user->is_admin) { ?>
    <div class="form-group">
        <div class="row">
            <input type="hidden" class="is_primary_contact_value" name="is_primary_contact_value-<?php echo $model_info->id; ?>" value="<?php echo $model_info->is_primary_contact; ?>" />

            <label for="is_primary_contact-<?php echo $model_info->id; ?>"  class="<?php echo $label_column; ?>"><?php echo app_lang('primary_contact'); ?></label>

            <div class="<?php echo $field_column; ?>">
                <?php
                //is set primary contact, disable the checkbox
                $disable = "";
                if ($model_info->is_primary_contact) {
                    $disable = "disabled='disabled'";
                }
                echo form_checkbox("is_primary_contact-$model_info->id", "1", $model_info->is_primary_contact, "id='is_primary_contact-$model_info->id' $disable class='is_primary_contact_lead form-check-input'");
                ?> 
            </div>
        </div>
    </div>
<?php } ?>

<?php if (!get_setting("disable_client_login")) { ?>
    <div class="form-group ">
        <div class="row">
            <label class="<?php echo $label_column; ?>" for="email_login_details-<?php echo $model_info->id; ?>"><?php echo app_lang('email_login_details'); ?></label>
            <div class="<?php echo $field_column; ?>">
                <?php echo form_checkbox("email_login_details-$model_info->id", "1", true, "id='email_login_details-$model_info->id' class='form-check-input'"); ?>
            </div>
        </div>
    </div>
<?php } ?>

<?php echo view("leads/custom_field_migration", array("custom_fields" => $custom_fields, "label_column" => $label_column, "field_column" => $field_column, "to_custom_field_type" => "client_contacts", "model_info" => $model_info)); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#generate_password-<?php echo $model_info->id; ?>").click(function () {
            $("#login_password-<?php echo $model_info->id; ?>").val(getRndomString(8));
        });
        $("#show_hide_password-<?php echo $model_info->id; ?>").click(function () {
            var $target = $("#login_password-<?php echo $model_info->id; ?>"),
                    type = $target.attr("type");
            if (type === "password") {
                $(this).attr("title", "<?php echo app_lang("hide_text"); ?>");
                $(this).html("<span data-feather='eye-off' class='icon-16'></span>");
                feather.replace();
                $target.attr("type", "text");
            } else if (type === "text") {
                $(this).attr("title", "<?php echo app_lang("show_text"); ?>");
                $(this).html("<span data-feather='eye' class='icon-16'></span>");
                feather.replace();
                $target.attr("type", "password");
            }
        });
    });
</script>    