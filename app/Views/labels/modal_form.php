<?php echo form_open(get_uri("labels/save"), array("id" => "labels-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix label-modal-body">
    <div class="container-fluid">
        <input type="hidden" id="type" name="type" value="<?php echo $type; ?>" />
        <input type="hidden" id="label_id" name="id" value="" />

        <div class="add-label clearfix pb10">
            <div class="form-group text-center">
                <div class="col-md-12">
                    <?php echo view("includes/color_plate"); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-10">
                    <div class="form-group">
                        <div class=" col-md-12">
                            <?php
                            echo form_input(array(
                                "id" => "title",
                                "name" => "title",
                                "value" => "",
                                "class" => "form-control",
                                "placeholder" => app_lang('label'),
                                "autofocus" => true,
                                "autocomplete" => "off",
                                "data-rule-required" => true,
                                "data-msg-required" => app_lang("field_required"),
                            ));
                            ?>
                        </div>
                    </div> 
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-default"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
                </div>
            </div>

        </div>

        <div id="label-show-area" class="p15 b-t"><?php echo $existing_labels; ?></div>

    </div>
</div>

<div class="modal-footer">
    <button id="label-delete-btn" type="button" class="btn btn-default hide float-start"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('delete'); ?></button>
    <button id="cancel-edit-btn" type="button" class="btn btn-default ml10 hide float-start"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('cancel'); ?></button>
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        var $labelShowArea = $("#label-show-area");

        $("#labels-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                if (result.success) {
                    if ($("#label_id").val()) {
                        var $selector = $labelShowArea.find("[data-id='" + result.id + "']");
                        $selector.fadeOut(100, function () {
                            $(result.data).insertAfter($selector);
                            $selector.remove();
                        });

                        hideEditMode();
                    } else {
                        $labelShowArea.prepend(result.data);
                    }

                    $("#title").val("").focus();
                }
            }
        });

        //update/delete
        $('body').on('click', "[data-act='label-edit-delete']", function () {
            showEditMode($(this));
        });

        //select texts inside the input field after clicking on color
        $(".color-palet span").click(function () {
            if ($("#title").val()) {
                $("#title").select();
            } else {
                $("#title").focus();
            }
        });

        function showEditMode($selector) {
            $("#title").val($selector.text()).focus();
            $("#label_id").val($selector.attr("data-id"));
            $(".color-palet span").removeClass("active");
            $(".color-palet").find("[data-color='" + $selector.attr("data-color") + "']").addClass("active");
            $("#color").val($selector.attr("data-color"));
            $("#label-delete-btn").removeClass("hide");
            $("#cancel-edit-btn").removeClass("hide");
        }

        function hideEditMode() {
            $("#title").val('').focus();
            $("#label_id").val('');
            $("#label-delete-btn").addClass("hide");
            $("#cancel-edit-btn").addClass("hide");
        }

        $("#cancel-edit-btn").click(function () {
            hideEditMode();
        });

        $("#label-delete-btn").click(function () {
            appLoader.show({container: ".label-modal-body", css: "left:0;"});

            $.ajax({
                url: "<?php echo get_uri('labels/delete') ?>",
                type: 'POST',
                dataType: 'json',
                data: {id: $("#label_id").val(), type: $("#type").val()},
                success: function (result) {
                    appLoader.hide();

                    if (result.label_exists) {
                        appAlert.error(result.message, {container: '.modal-body', animate: false});
                    } else if (result.success) {
                        var $selector = $labelShowArea.find("[data-id='" + result.id + "']");
                        $selector.fadeOut(100, function () {
                            $selector.remove();
                        });

                        hideEditMode();
                    }
                }
            });
        });

        setTimeout(function () {
            $("#title").focus();
        }, 200);
    });
</script>