<?php echo form_open(get_uri("orders/save"), array("id" => "order-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

        <div class="form-group">
            <div class="row">
                <label for="order_date" class=" col-md-3"><?php echo app_lang('order_date'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "order_date",
                        "name" => "order_date",
                        "value" => $model_info->order_date,
                        "class" => "form-control",
                        "placeholder" => app_lang('order_date'),
                        "autocomplete" => "off",
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>

        <?php if ($client_id) { ?>
            <input type="hidden" name="order_client_id" value="<?php echo $client_id; ?>" />
        <?php } else { ?>
            <div class="form-group">
                <div class="row">
                    <label for="order_client_id" class=" col-md-3"><?php echo app_lang('client'); ?></label>
                    <div class="col-md-9">
                        <?php
                        echo form_dropdown("order_client_id", $clients_dropdown, array($model_info->client_id), "class='select2 validate-hidden' id='order_client_id' data-rule-required='true', data-msg-required='" . app_lang('field_required') . "'");
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="form-group">
            <div class="row">
                <label for="status_id" class="col-md-3"><?php echo app_lang('status'); ?></label>
                <div class="col-md-9">
                    <?php
                    foreach ($order_statuses as $status) {
                        $order_status[$status->id] = $status->title;
                    }

                    echo form_dropdown("status_id", $order_status, array($model_info->status_id), "class='select2'");
                    ?>
                </div>
            </div>
        </div>

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
                <label for="order_note" class=" col-md-3"><?php echo app_lang('note'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_textarea(array(
                        "id" => "order_note",
                        "name" => "order_note",
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

    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#order-form").appForm({
            onSuccess: function (result) {
                if (typeof RELOAD_VIEW_AFTER_UPDATE !== "undefined" && RELOAD_VIEW_AFTER_UPDATE) {
                    location.reload();
                } else {
                    window.location = "<?php echo site_url('orders/view'); ?>/" + result.id;
                }
            }
        });

        $("#order-form .select2").select2();

        setDatePicker("#order_date");

    });
</script>