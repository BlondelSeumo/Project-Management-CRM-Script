<?php echo form_open(get_uri("settings/save_footer_menu"), array("id" => "footer-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="type" value="data" />
        <div class="row">
            <div class="col-md-6 form-group">
                <?php
                echo form_input(array(
                    "id" => "menu_name",
                    "name" => "menu_name",
                    "class" => "form-control",
                    "placeholder" => app_lang('menu_name'),
                    "value" => $model_info->menu_name,
                    "data-rule-required" => true,
                    "data-msg-required" => app_lang("field_required"),
                ));
                ?>
            </div>
            <div class="col-md-6 form-group">
                <?php
                echo form_input(array(
                    "id" => "url",
                    "name" => "url",
                    "class" => "form-control",
                    "placeholder" => "URL",
                    "value" => $model_info->url,
                    "data-rule-required" => true,
                    "data-msg-required" => app_lang("field_required"),
                ));
                ?>
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
        $("#footer-form").appForm({
            onSuccess: function (result) {
                var $item = $("#footer-menus-show-area").find("[data-footer_menu_temp_id='" + window.footerMenuItemTempId + "']");
                $item.html(result.data);

                saveMenusPosition();
                window.footerMenuItemTempId = "";
            }
        });
    });
</script>