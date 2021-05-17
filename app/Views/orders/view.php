<div id="page-content" class="clearfix">
    <div style="max-width: 1000px; margin: auto;">
        <div class="page-title clearfix mt25">
            <h1><?php echo get_order_id($order_info->id); ?></h1>
            <div class="title-button-group">
                <span class="dropdown inline-block">
                    <button class="btn btn-info text-white dropdown-toggle caret mt0 mb0" type="button" data-bs-toggle="dropdown" aria-expanded="true">
                        <i data-feather="tool" class="icon-16"></i> <?php echo app_lang('actions'); ?>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li role="presentation"><?php echo anchor(get_uri("orders/download_pdf/" . $order_info->id), "<i data-feather='download' class='icon-16'></i> " . app_lang('download_pdf'), array("title" => app_lang('download_pdf'), "class" => "dropdown-item")); ?> </li>
                        <li role="presentation"><?php echo anchor(get_uri("orders/download_pdf/" . $order_info->id . "/view"), "<i data-feather='file-text' class='icon-16'></i> " . app_lang('view_pdf'), array("title" => app_lang('view_pdf'), "target" => "_blank", "class" => "dropdown-item")); ?> </li>
                        <li role="presentation"><?php echo anchor(get_uri("orders/preview/" . $order_info->id . "/1"), "<i data-feather='search' class='icon-16'></i> " . app_lang('order_preview'), array("title" => app_lang('order_preview'), "target" => "_blank", "class" => "dropdown-item")); ?> </li>
                        <li role="presentation" class="dropdown-divider"></li>
                        <li role="presentation"><?php echo modal_anchor(get_uri("orders/modal_form"), "<i data-feather='edit' class='icon-16'></i> " . app_lang('edit_order'), array("title" => app_lang('edit_order'), "data-post-id" => $order_info->id, "role" => "menuitem", "tabindex" => "-1", "class" => "dropdown-item")); ?> </li>

                        <li role="presentation" class="dropdown-divider"></li>
                        <?php if ($show_estimate_option) { ?>
                            <li role="presentation"><?php echo modal_anchor(get_uri("estimates/modal_form/"), "<i data-feather='file' class='icon-16'></i> " . app_lang('create_estimate'), array("title" => app_lang("create_estimate"), "data-post-order_id" => $order_info->id, "class" => "dropdown-item")); ?> </li>
                        <?php } ?>
                        <?php if ($show_invoice_option) { ?>
                            <li role="presentation"><?php echo modal_anchor(get_uri("invoices/modal_form/"), "<i data-feather='file-text' class='icon-16'></i> " . app_lang('create_invoice'), array("title" => app_lang("create_invoice"), "data-post-order_id" => $order_info->id, "class" => "dropdown-item")); ?> </li>
                        <?php } ?>

                    </ul>
                </span>
                <?php echo modal_anchor(get_uri("orders/item_modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_item'), array("class" => "btn btn-default", "title" => app_lang('add_item'), "data-post-order_id" => $order_info->id)); ?>
            </div>
        </div>
        <div id="order-status-bar">
            <?php echo view("orders/order_status_bar"); ?>
        </div>
        <div class="mt15">
            <div class="card p15 b-t">
                <div class="clearfix p20">
                    <!-- small font size is required to generate the pdf, overwrite that for screen -->
                    <style type="text/css"> .invoice-meta {font-size: 100% !important;}</style>

                    <?php
                    $color = get_setting("order_color");
                    if (!$color) {
                        $color = get_setting("invoice_color");
                    }
                    $style = get_setting("invoice_style");
                    ?>
                    <?php
                    $data = array(
                        "client_info" => $client_info,
                        "color" => $color ? $color : "#2AA384",
                        "order_info" => $order_info
                    );
                    if ($style === "style_2") {
                        echo view('orders/order_parts/header_style_2.php', $data);
                    } else {
                        echo view('orders/order_parts/header_style_1.php', $data);
                    }
                    ?>

                </div>

                <div class="table-responsive mt15 pl15 pr15">
                    <table id="order-item-table" class="display" width="100%">            
                    </table>
                </div>

                <div class="clearfix">
                    <div class="col-sm-8">

                    </div>
                    <div class="float-end pr15" id="order-total-section">
                        <?php echo view("orders/order_total_section"); ?>
                    </div>
                </div>

                <p class="b-t b-info pt10 m15"><?php echo nl2br($order_info->note); ?></p>

            </div>
        </div>

    </div>
</div>



<script type="text/javascript">
    //RELOAD_VIEW_AFTER_UPDATE = true;
    $(document).ready(function () {
        $("#order-item-table").appTable({
            source: '<?php echo_uri("orders/item_list_data/" . $order_info->id . "/") ?>',
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

<?php
//required to send email 

load_css(array(
    "assets/js/summernote/summernote.css",
));
load_js(array(
    "assets/js/summernote/summernote.min.js",
));
?>

<?php echo view("orders/update_order_status_script", array("details_view" => true)); ?>