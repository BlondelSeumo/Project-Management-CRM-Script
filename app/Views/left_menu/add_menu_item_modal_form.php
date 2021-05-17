<?php echo form_open(get_uri("left_menus/prepare_custom_menu_item_data"), array("id" => "custom-menu-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <div class="form-group">
            <div class="row">
                <input type="hidden" name="is_sub_menu" value="<?php echo $model_info->is_sub_menu; ?>" />
                <label for="title" class=" col-md-3"><?php echo app_lang('title'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "title",
                        "name" => "title",
                        "value" => $model_info->title,
                        "class" => "form-control",
                        "placeholder" => app_lang('title'),
                        "autofocus" => true,
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="url" class=" col-md-3"><?php echo app_lang('url'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "url",
                        "name" => "url",
                        "value" => $model_info->url,
                        "class" => "form-control",
                        "placeholder" => app_lang('url'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="open_in_new_tab" class=" col-md-3"><?php echo app_lang('open_in_new_tab'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_checkbox("open_in_new_tab", "1", $model_info->open_in_new_tab ? true : false, "id='open_in_new_tab' class='form-check-input'");
                    ?>                       
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-12">
                <?php echo view("left_menu/icon_plate"); ?>
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
        $("#custom-menu-form").appForm({
            onSuccess: function (result) {
                if (result.success) {
                    addOrUpdateCustomMenuItem(result.item_data);
                    saveItemsPosition();
                }
            }
        });

        setTimeout(function () {
            $("#title").focus();
        }, 200);
    });
</script>