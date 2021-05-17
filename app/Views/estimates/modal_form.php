<?php echo form_open(get_uri("estimates/save"), array("id" => "estimate-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
        <input type="hidden" name="estimate_request_id" value="<?php echo $estimate_request_id; ?>" />

        <?php if ($is_clone) { ?>
            <input type="hidden" name="is_clone" value="1" />
            <input type="hidden" name="discount_amount" value="<?php echo $model_info->discount_amount; ?>" />
            <input type="hidden" name="discount_amount_type" value="<?php echo $model_info->discount_amount_type; ?>" />
            <input type="hidden" name="discount_type" value="<?php echo $model_info->discount_type; ?>" />
        <?php } ?>

        <div class="form-group">
            <div class="row">
                <label for="estimate_date" class=" col-md-3"><?php echo app_lang('estimate_date'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "estimate_date",
                        "name" => "estimate_date",
                        "value" => $model_info->estimate_date,
                        "class" => "form-control",
                        "placeholder" => app_lang('estimate_date'),
                        "autocomplete" => "off",
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="valid_until" class=" col-md-3"><?php echo app_lang('valid_until'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "valid_until",
                        "name" => "valid_until",
                        "value" => $model_info->valid_until,
                        "class" => "form-control",
                        "placeholder" => app_lang('valid_until'),
                        "autocomplete" => "off",
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                        "data-rule-greaterThanOrEqual" => "#estimate_date",
                        "data-msg-greaterThanOrEqual" => app_lang("end_date_must_be_equal_or_greater_than_start_date")
                    ));
                    ?>
                </div>
            </div>
        </div>
        <?php if ($client_id) { ?>
            <input type="hidden" name="estimate_client_id" value="<?php echo $client_id; ?>" />
        <?php } else { ?>
            <div class="form-group">
                <div class="row">
                    <label for="estimate_client_id" class=" col-md-3"><?php echo app_lang('client'); ?></label>
                    <div class="col-md-9">
                        <?php
                        echo form_dropdown("estimate_client_id", $clients_dropdown, array($model_info->client_id), "class='select2 validate-hidden' id='estimate_client_id' data-rule-required='true', data-msg-required='" . app_lang('field_required') . "'");
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="form-group">
            <div class="row">
                <label for="tax_id" class=" col-md-3"><?php echo app_lang('tax'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown("tax_id", $taxes_dropdown, array($model_info->tax_id), "class='select2 tax-select2'");
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="tax_id" class=" col-md-3"><?php echo app_lang('second_tax'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown("tax_id2", $taxes_dropdown, array($model_info->tax_id2), "class='select2 tax-select2'");
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="estimate_note" class=" col-md-3"><?php echo app_lang('note'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_textarea(array(
                        "id" => "estimate_note",
                        "name" => "estimate_note",
                        "value" => $model_info->note ? $model_info->note : "",
                        "class" => "form-control",
                        "placeholder" => app_lang('note'),
                        "data-rich-text-editor" => true
                    ));
                    ?>
                </div>
            </div>
        </div>

        <?php echo view("custom_fields/form/prepare_context_fields", array("custom_fields" => $custom_fields, "label_column" => "col-md-3", "field_column" => " col-md-9")); ?> 

        <?php if ($is_clone) { ?>
            <div class="form-group">
                <div class="row">
                    <label for="copy_items"class=" col-md-12">
                        <?php
                        echo form_checkbox("copy_items", "1", true, "id='copy_items' disabled='disabled' class='float-start mr15 form-check-input'");
                        ?>    
                        <?php echo app_lang('copy_items'); ?>
                    </label>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="copy_discount"class=" col-md-12">
                        <?php
                        echo form_checkbox("copy_discount", "1", true, "id='copy_discount' disabled='disabled' class='float-start mr15 form-check-input'");
                        ?>    
                        <?php echo app_lang('copy_discount'); ?>
                    </label>
                </div>
            </div>
        <?php } ?> 

        <?php if ($order_id) { ?>
            <div class="form-group">
                <div class="row">
                    <label for="order_id_checkbox" class=" col-md-12">
                        <input type="hidden" name="copy_items_from_order" value="<?php echo $order_id; ?>" />
                        <?php
                        echo form_checkbox("order_id_checkbox", $order_id, true, " class='float-start form-check-input' disabled='disabled'");
                        ?>    
                        <span class="float-start ml15"> <?php echo app_lang('include_all_items_of_this_order'); ?> </span>
                    </label>
                </div>
            </div>
        <?php } ?>

    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#estimate-form").appForm({
            onSuccess: function (result) {
                if (typeof RELOAD_VIEW_AFTER_UPDATE !== "undefined" && RELOAD_VIEW_AFTER_UPDATE) {
                    location.reload();
                } else {
                    window.location = "<?php echo site_url('estimates/view'); ?>/" + result.id;
                }
            }
        });
        $("#estimate-form .tax-select2").select2();
        $("#estimate_client_id").select2();

        setDatePicker("#estimate_date, #valid_until");


    });
</script>