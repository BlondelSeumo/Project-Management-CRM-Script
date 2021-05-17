<div class="modal-body clearfix general-form">
    <div class="container-fluid">
        <div class="form-group">
            <div class="row">
                <div  class="col-md-12">
                    <strong><?php echo $model_info->title; ?></strong>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb15">
                <?php echo $model_info->content; ?>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <?php
    echo js_anchor("<i data-feather='x-circle' class='icon-16'></i> " . app_lang('delete_widget'), array("class" => "btn btn-default float-start", "id" => "delete_widget"));

    echo modal_anchor(get_uri("dashboard/custom_widget_modal_form/" . $model_info->id), "<i data-feather='edit' class='icon-16'></i> " . app_lang('edit_widget'), array("class" => "btn btn-default", "title" => app_lang('edit_widget')));
    ?>
    <button type="button" class="btn btn-default close-modal" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
</div>

<?php echo view("dashboards/helper_js"); ?>

<script>
    $(document).ready(function () {

        $('#delete_widget').click(function () {
            $(this).appConfirmation({
                title: "<?php echo app_lang('are_you_sure'); ?>",
                btnConfirmLabel: "<?php echo app_lang('yes'); ?>",
                btnCancelLabel: "<?php echo app_lang('no'); ?>",
                onConfirm: function () {
                    $('.close-modal').trigger("click");
                    $.ajax({
                        url: "<?php echo get_uri('dashboard/delete_custom_widgets') ?>",
                        type: 'POST',
                        dataType: 'json',
                        data: {id: <?php echo $model_info->id; ?>},
                        success: function (result) {
                            if (result.success) {
                                var $widgetRow = $(".js-widget-container, #widget-column-container").find('[data-value="' + result.id + '"]');
                                $widgetRow.fadeOut(300, function () {
                                    $widgetRow.remove();
                                });

                                setTimeout(function () {
                                    addEmptyAreaText($widgetRow.closest("div.add-column-panel"));
                                    saveWidgetPosition();
                                }, 300);

                                appAlert.warning(result.message, {duration: 10000});
                            } else {
                                appAlert.error(result.message);
                            }
                        }
                    });

                }
            });

            return false;
        });

    });
</script>