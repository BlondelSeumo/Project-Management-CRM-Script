<?php echo form_open(get_uri("orders/save_item"), array("id" => "order-item-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
        <input type="hidden" id="item_id" name="item_id" value="" />

        <input type="hidden" name="order_id" value="<?php echo $order_id; ?>" />
        <input type="hidden" name="add_new_item_to_library" value="" id="add_new_item_to_library" />

        <div class="form-group">
            <div class="row">
                <label for="order_item_title" class=" col-md-3"><?php echo app_lang('item'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "order_item_title",
                        "name" => "order_item_title",
                        "value" => $model_info->title,
                        "class" => "form-control validate-hidden",
                        "placeholder" => app_lang('select_or_create_new_item'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                    <a id="order_item_title_dropdwon_icon" tabindex="-1" href="javascript:void(0);" style="color: #B3B3B3;float: right; padding: 5px 7px; margin-top: -35px; font-size: 18px;"><span>×</span></a>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="order_item_description" class="col-md-3"><?php echo app_lang('description'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_textarea(array(
                        "id" => "order_item_description",
                        "name" => "order_item_description",
                        "value" => $model_info->description ? $model_info->description : "",
                        "class" => "form-control",
                        "placeholder" => app_lang('description'),
                        "data-rich-text-editor" => true
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="order_item_quantity" class=" col-md-3"><?php echo app_lang('quantity'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "order_item_quantity",
                        "name" => "order_item_quantity",
                        "value" => $model_info->quantity ? to_decimal_format($model_info->quantity) : "",
                        "class" => "form-control",
                        "placeholder" => app_lang('quantity'),
                        "type" => "number",
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="order_unit_type" class=" col-md-3"><?php echo app_lang('unit_type'); ?></label>
                <div class="col-md-9">
                    <?php
                    $form_input_array = array(
                        "id" => "order_unit_type",
                        "name" => "order_unit_type",
                        "value" => $model_info->unit_type,
                        "class" => "form-control",
                        "placeholder" => app_lang('unit_type') . ' (Ex: hours, pc, etc.)'
                    );

                    if ($login_user->user_type === "client") {
                        $form_input_array["disabled"] = "disabled";
                    }

                    echo form_input($form_input_array);
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="order_item_rate" class=" col-md-3"><?php echo app_lang('rate'); ?></label>
                <div class="col-md-9">
                    <?php
                    $form_input_array = array(
                        "id" => "order_item_rate",
                        "name" => "order_item_rate",
                        "value" => $model_info->rate ? to_decimal_format($model_info->rate) : "",
                        "class" => "form-control",
                        "placeholder" => app_lang('rate'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    );

                    if ($login_user->user_type === "client") {
                        $form_input_array["disabled"] = "disabled";
                    }

                    echo form_input($form_input_array);
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
        $("#order-item-form").appForm({
            onSuccess: function (result) {
                $("#order-item-table").appTable({newData: result.data, dataId: result.id});
                $("#order-total-section").html(result.order_total_view);
            }
        });

        //show item suggestion dropdown when adding new item
        var isUpdate = "<?php echo $model_info->id; ?>";
        if (!isUpdate) {
            applySelect2OnItemTitle();
        }

        //re-initialize item suggestion dropdown on request
        $("#order_item_title_dropdwon_icon").click(function () {
            applySelect2OnItemTitle();
        });

    });

    function applySelect2OnItemTitle() {
        $("#order_item_title").select2({
            showSearchBox: true,
            ajax: {
                url: "<?php echo get_uri("orders/get_order_item_suggestion"); ?>",
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term // search term
                    };
                },
                results: function (data, page) {
                    return {results: data};
                }
            }
        }).change(function (e) {
            if (e.val === "+") {
                //show simple textbox to input the new item
                $("#order_item_title").select2("destroy").val("").focus();
                $("#add_new_item_to_library").val(1); //set the flag to add new item in library
            } else if (e.val) {
                //get existing item info
                $("#add_new_item_to_library").val(""); //reset the flag to add new item in library
                $.ajax({
                    url: "<?php echo get_uri("orders/get_order_item_info_suggestion"); ?>",
                    data: {item_name: e.val},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {

                        //auto fill the description, unit type and rate fields.
                        if (response && response.success) {
                            $("#item_id").val(response.item_info.id); //store item id when it's adding by clients
                            
                            if (!$("#order_item_description").val()) {
                                $("#order_item_description").val(response.item_info.description);
                            }

                            if (!$("#order_unit_type").val()) {
                                $("#order_unit_type").val(response.item_info.unit_type);
                            }

                            if (!$("#order_item_rate").val()) {
                                $("#order_item_rate").val(response.item_info.rate);
                            }
                        }
                    }
                });
            }

        });
    }

</script>