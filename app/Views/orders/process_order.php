<div id="page-content" class="page-wrapper clearfix">
    <div class="process-order-preview">
        <div class="card">

            <?php echo form_open(get_uri("orders/place_order"), array("id" => "place-order-form", "class" => "general-form", "role" => "form")); ?>

            <div class="page-title clearfix">
                <h1> <?php echo app_lang('process_order'); ?></h1>
            </div>

            <div class="p20">

                <div class="mb20 ml15 mr15"><?php echo app_lang("process_order_info_message"); ?></div>

                <div class="m15 pb15 mb30">
                    <div class="table-responsive">
                        <table id="order-item-table" class="display mt0" width="100%">            
                        </table>
                    </div>
                    <div class="clearfix row">
                        <div class="col-sm-8">

                        </div>
                        <div class="float-end" id="order-total-section">
                            <?php echo view("orders/processing_order_total_section"); ?>
                        </div>
                    </div>
                </div>

                <div class="pl15 pr15">
                    <?php if (isset($clients_dropdown) && $clients_dropdown) { ?>
                        <div class="form-group mt15 clearfix">
                            <div class="row">
                                <label for="client_id" class=" col-md-3"><?php echo app_lang('client'); ?></label>
                                <div class="col-md-9">
                                    <?php
                                    echo form_dropdown("client_id", $clients_dropdown, array(), "class='select2 validate-hidden' id='client_id' data-rule-required='true', data-msg-required='" . app_lang('field_required') . "'");
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="form-group clearfix">
                        <div class="row">
                            <label for="order_note" class=" col-md-3"><?php echo app_lang('note'); ?></label>
                            <div class=" col-md-9">
                                <?php
                                echo form_textarea(array(
                                    "id" => "order_note",
                                    "name" => "order_note",
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

            <div class="card-footer clearfix">
                <button type="submit" class="btn btn-primary float-end ml10"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('place_order'); ?></button>
                <?php echo modal_anchor(get_uri("orders/item_modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_item'), array("class" => "btn btn-default float-end ml10", "title" => app_lang('add_item'))); ?>
                <?php echo anchor(get_uri("items/grid_view"), "<i data-feather='search' class='icon-16'></i> " . app_lang('find_more_items'), array("class" => "btn btn-default float-end")); ?> 
            </div>

            <?php echo form_close(); ?>

        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            $("#place-order-form").appForm({
                isModal: false,
                onSubmit: function () {
                    appLoader.show();
                    $("#place-order-form").find('[type="submit"]').attr('disabled', 'disabled');
                },
                onSuccess: function (result) {
                    appLoader.hide();
                    window.location = result.redirect_to;
                }
            });

            $("#client_id").select2();

            $("#order-item-table").appTable({
                source: '<?php echo_uri("orders/item_list_data_of_login_user") ?>',
                order: [[0, "asc"]],
                hideTools: true,
                displayLength: 100,
                columns: [
                    {visible: false, searchable: false},
                    {title: "<?php echo app_lang("item") ?> ", "bSortable": false},
                    {title: "<?php echo app_lang("quantity") ?>", "class": "text-right w15p", "bSortable": false},
                    {title: "<?php echo app_lang("rate") ?>", "class": "text-right w15p", "bSortable": false},
                    {title: "<?php echo app_lang("total") ?>", "class": "text-right w15p", "bSortable": false},
                    {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100", "bSortable": false}
                ],

                onInitComplete: function () {
                    //apply sortable
                    $("#order-item-table").find("tbody").attr("id", "order-item-table-sortable");
                    var $selector = $("#order-item-table-sortable");

                    Sortable.create($selector[0], {
                        animation: 150,
                        chosenClass: "sortable-chosen",
                        ghostClass: "sortable-ghost",
                        onUpdate: function (e) {
                            appLoader.show();
                            //prepare sort indexes 
                            var data = "";
                            $.each($selector.find(".item-row"), function (index, ele) {
                                if (data) {
                                    data += ",";
                                }

                                data += $(ele).attr("data-id") + "-" + index;
                            });

                            //update sort indexes
                            $.ajax({
                                url: '<?php echo_uri("orders/update_item_sort_values") ?>',
                                type: "POST",
                                data: {sort_values: data},
                                success: function () {
                                    appLoader.hide();
                                }
                            });
                        }
                    });
                },

                onDeleteSuccess: function (result) {
                    $("#order-total-section").html(result.order_total_view);
                },
                onUndoSuccess: function (result) {
                    $("#order-total-section").html(result.order_total_view);
                }
            });
        });
    </script>

</div>