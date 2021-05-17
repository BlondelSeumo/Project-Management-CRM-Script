<div id="page-content" class="page-wrapper clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "ip_restriction";
            echo view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <?php echo form_open(get_uri("settings/save_ip_settings"), array("id" => "ip-settings-form", "class" => "general-form", "role" => "form")); ?>
            <div class="card">
                <div class="card-header">
                    <h4><?php echo app_lang("ip_restriction"); ?></h4>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="pb15 col-md-12 clearfix">
                            <strong> <?php echo app_lang("allow_timecard_access_from_these_ips_only"); ?></strong>
                        </div>
                        <div class=" col-md-12 clearfix">
                            <?php
                            echo form_textarea(array(
                                "id" => "allowed_ip_addresses",
                                "name" => "allowed_ip_addresses",
                                "value" => get_setting("allowed_ip_addresses"),
                                "class" => "form-control",
                                "style" => "min-height:100px"
                            ));
                            ?>
                        </div>
                        <div class="pt5 col-md-12 clearfix">
                            <i data-feather="info" class="icon-16"></i> <?php echo app_lang("enter_one_ip_per_line"); ?>
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
        $("#ip-settings-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });
    });
</script>