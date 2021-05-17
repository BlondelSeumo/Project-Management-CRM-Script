<?php echo form_open(get_uri("estimates/save_discount"), array("id" => "discount-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="estimate_id" value="<?php echo $model_info->id; ?>" />
        <div class="form-group">
            <div class="row">
                <label for="discount_type" class="col-md-3"><?php echo app_lang('discount_type'); ?></label>
                <div class="col-md-9">
                    <?php
                    $discount_type_dropdown = array("before_tax" => app_lang("before_tax"), "after_tax" => app_lang("after_tax"));
                    echo form_dropdown("discount_type", $discount_type_dropdown, $model_info->discount_type, "class='select2'");
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="discount" class="col-md-3"><?php echo app_lang('discount'); ?></label>
                <div class="col-md-4">
                    <?php
                    echo form_input(array(
                        "id" => "discount",
                        "name" => "discount_amount",
                        "value" => $model_info->discount_amount ? $model_info->discount_amount : "",
                        "class" => "form-control",
                        "autofocus" => "true",
                        "placeholder" => app_lang('discount'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
                <div class="col-md-5">
                    <?php
                    $discount_percentage_dropdown = array("percentage" => app_lang("percentage"), "fixed_amount" => app_lang("fixed_amount"));
                    echo form_dropdown("discount_amount_type", $discount_percentage_dropdown, $model_info->discount_amount_type, "class='select2'");
                    ?>
                </div>
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
        $("#discount-form").appForm({
            onSuccess: function (result) {
                if (result.success && result.estimate_total_view) {
                    $("#estimate-total-section").html(result.estimate_total_view);
                } else {
                    appAlert.error(result.message);
                }
            }
        });

        $("#discount-form .select2").select2();
    });

</script>