<?php echo form_open(get_uri("dashboard/save_custom_widget"), array("id" => "custom_widget-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
        <div class="form-group">
            <div class="row">
                <label for="title" class=" col-md-3"><?php echo app_lang('title'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "title",
                        "name" => "title",
                        "class" => "form-control",
                        "placeholder" => app_lang("title"),
                        "value" => $model_info->title,
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required")
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="content" class=" col-md-3"><?php echo app_lang('content'); ?></label>
                <div class=" col-md-9">
                    <div class="notepad">
                        <?php
                        echo form_textarea(array(
                            "name" => "content",
                            "value" => $model_info->content,
                            "class" => "form-control",
                            "placeholder" => app_lang('content') . "...",
                            "data-rule-required" => true,
                            "data-msg-required" => app_lang("field_required"),
                            "data-rich-text-editor" => true
                        ));
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="show_title" class="col-md-3"><?php echo app_lang('show_title'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_checkbox("show_title", "1", $model_info->show_title ? true : false, "id='show_title' class='form-check-input'");
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="show_border" class="col-md-3"><?php echo app_lang('show_border'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_checkbox("show_border", "1", $model_info->show_border ? true : false, "id='show_border' class='form-check-input'");
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <div id="link-of-widget-view" class="hide">
        <?php
        echo modal_anchor(get_uri("dashboard/view_custom_widget"), "", array());
        ?>
    </div>
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button id="save-and-show-widget-button" type="button" class="btn btn-info text-white"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save_and_show'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script>
    $(document).ready(function () {
        //send data to show the widget after save
        window.showAddNewModal = false;

        $("#save-and-show-widget-button").click(function () {
            window.showAddNewModal = true;
            $(this).trigger("submit");
        });

        var widgetInfoText = "<?php echo app_lang('custom_widget_details') ?>";

        window.widgetForm = $("#custom_widget-form").appForm({
            closeModalOnSuccess: false,
            onSuccess: function (result) {
                setTimeout(function () {
                    saveWidgetPosition();
                }, 300);

                var widgetRow = $(".js-widget-container, #widget-column-container").find('[data-value="' + result.id + '"]');

                if (widgetRow.has("span").length < 1) {
                    //insert operation
                    $(".js-widget-container").append(result.custom_widgets_row);
                } else {
                    //update operation
                    widgetRow.html(result.custom_widgets_data);
                }

                $(".js-widget-container").find("span.empty-area-text").remove();

                appAlert.success(result.message, {duration: 10000});

                if (window.showAddNewModal) {
                    var $widgetViewLink = $("#link-of-widget-view").find("a");
                    $widgetViewLink.attr("data-title", widgetInfoText);
                    $widgetViewLink.attr("data-post-id", result.id);

                    $widgetViewLink.trigger("click");
                } else {
                    window.widgetForm.closeModal();
                }
            }
        });
    });
</script>    