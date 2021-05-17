<div id="page-content" class="page-wrapper clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "company";
            echo view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <?php echo form_open(get_uri("settings/save_company_settings"), array("id" => "company-settings-form", "class" => "general-form dashed-row", "role" => "form")); ?>
            <div class="card">
                <div class=" card-header">
                    <h4><?php echo app_lang("company_settings"); ?></h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="row">
                            <label for="company_name" class=" col-md-2"><?php echo app_lang('company_name'); ?></label>
                            <div class=" col-md-10">
                                <?php
                                echo form_input(array(
                                    "id" => "company_name",
                                    "name" => "company_name",
                                    "value" => get_setting("company_name"),
                                    "class" => "form-control",
                                    "placeholder" => app_lang('company_name'),
                                    "data-rule-required" => true,
                                    "data-msg-required" => app_lang("field_required")
                                ));
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="company_address" class=" col-md-2"><?php echo app_lang('address'); ?></label>
                            <div class=" col-md-10">
                                <?php
                                echo form_textarea(array(
                                    "id" => "company_address",
                                    "name" => "company_address",
                                    "value" => get_setting("company_address"),
                                    "class" => "form-control",
                                    "placeholder" => app_lang('address'),
                                ));
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="company_phone" class=" col-md-2"><?php echo app_lang('phone'); ?></label>
                            <div class=" col-md-10">
                                <?php
                                echo form_input(array(
                                    "id" => "company_phone",
                                    "name" => "company_phone",
                                    "value" => get_setting("company_phone"),
                                    "class" => "form-control",
                                    "placeholder" => app_lang('phone')
                                ));
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="company_email" class=" col-md-2"><?php echo app_lang('email'); ?></label>
                            <div class=" col-md-10">
                                <?php
                                echo form_input(array(
                                    "id" => "company_email",
                                    "name" => "company_email",
                                    "value" => get_setting("company_email"),
                                    "class" => "form-control",
                                    "placeholder" => app_lang('email')
                                ));
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="company_website" class=" col-md-2"><?php echo app_lang('website'); ?></label>
                            <div class=" col-md-10">
                                <?php
                                echo form_input(array(
                                    "id" => "company_website",
                                    "name" => "company_website",
                                    "value" => get_setting("company_website"),
                                    "class" => "form-control",
                                    "placeholder" => app_lang('website')
                                ));
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label for="company_vat_number" class=" col-md-2"><?php echo app_lang('vat_number'); ?></label>
                            <div class=" col-md-10">
                                <?php
                                echo form_input(array(
                                    "id" => "company_vat_number",
                                    "name" => "company_vat_number",
                                    "value" => get_setting("company_vat_number"),
                                    "class" => "form-control",
                                    "placeholder" => app_lang('vat_number')
                                ));
                                ?>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#company-settings-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });

    });
</script>