<div id="page-content" class="clearfix">
    <div style="max-width: 1000px; margin: auto;">
        <div class="page-title clearfix mt25">
            <h1><?php echo get_invoice_id($invoice_info->id); ?>
                <?php
                if ($invoice_info->recurring) {
                    $recurring_status_class = "text-primary";
                    if ($invoice_info->no_of_cycles_completed > 0 && $invoice_info->no_of_cycles_completed == $invoice_info->no_of_cycles) {
                        $recurring_status_class = "text-danger";
                    }
                    ?>
                    <span class="label ml15 b-a "><span class="<?php echo $recurring_status_class; ?>"><?php echo app_lang('recurring'); ?></span></span>
                <?php } ?>
            </h1>
            <div class="title-button-group">
                <span class="dropdown inline-block mt10">
                    <button class="btn btn-info text-white dropdown-toggle caret mt0 mb0" type="button" data-bs-toggle="dropdown" aria-expanded="true">
                        <i data-feather="tool" class="icon-16"></i> <?php echo app_lang('actions'); ?>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <?php if ($invoice_status !== "cancelled" && $can_edit_invoices) { ?>
                            <li role="presentation"><?php echo modal_anchor(get_uri("invoices/send_invoice_modal_form/" . $invoice_info->id), "<i data-feather='mail' class='icon-16'></i> " . app_lang('email_invoice_to_client'), array("title" => app_lang('email_invoice_to_client'), "data-post-id" => $invoice_info->id, "role" => "menuitem", "tabindex" => "-1", "class" => "dropdown-item")); ?> </li>
                        <?php } ?>
                        <li role="presentation"><?php echo anchor(get_uri("invoices/download_pdf/" . $invoice_info->id), "<i data-feather='download' class='icon-16'></i> " . app_lang('download_pdf'), array("title" => app_lang('download_pdf'), "class" => "dropdown-item")); ?> </li>
                        <li role="presentation"><?php echo anchor(get_uri("invoices/download_pdf/" . $invoice_info->id . "/view"), "<i data-feather='file-text' class='icon-16'></i> " . app_lang('view_pdf'), array("title" => app_lang('view_pdf'), "target" => "_blank", "class" => "dropdown-item")); ?> </li>
                        <li role="presentation"><?php echo anchor(get_uri("invoices/preview/" . $invoice_info->id . "/1"), "<i data-feather='search' class='icon-16'></i> " . app_lang('invoice_preview'), array("title" => app_lang('invoice_preview'), "target" => "_blank", "class" => "dropdown-item")); ?> </li>
                        <li role="presentation"><?php echo js_anchor("<i data-feather='printer' class='icon-16'></i> " . app_lang('print_invoice'), array('title' => app_lang('print_invoice'), 'id' => 'print-invoice-btn', "class" => "dropdown-item")); ?> </li>

                        <?php if ($can_edit_invoices) { ?>
                            <li role="presentation" class="dropdown-divider"></li>

                            <?php if ($invoice_status !== "cancelled") { ?>
                                <li role="presentation"><?php echo modal_anchor(get_uri("invoices/modal_form"), "<i data-feather='edit' class='icon-16'></i> " . app_lang('edit_invoice'), array("title" => app_lang('edit_invoice'), "data-post-id" => $invoice_info->id, "role" => "menuitem", "tabindex" => "-1", "class" => "dropdown-item")); ?> </li>
                            <?php } ?>

                            <?php if ($invoice_status == "draft" && $invoice_status !== "cancelled") { ?>
                                <li role="presentation"><?php echo ajax_anchor(get_uri("invoices/update_invoice_status/" . $invoice_info->id . "/not_paid"), "<i data-feather='check' class='icon-16'></i> " . app_lang('mark_invoice_as_not_paid'), array("data-reload-on-success" => "1", "class" => "dropdown-item")); ?> </li>
                            <?php } else if ($invoice_status == "not_paid" || $invoice_status == "overdue" || $invoice_status == "partially_paid") { ?>
                                <li role="presentation"><?php echo js_anchor("<i data-feather='x' class='icon-16'></i> " . app_lang('mark_invoice_as_cancelled'), array('title' => app_lang('mark_invoice_as_cancelled'), "data-action-url" => get_uri("invoices/update_invoice_status/" . $invoice_info->id . "/cancelled"), "data-action" => "delete-confirmation", "data-reload-on-success" => "1", "class" => "dropdown-item")); ?> </li>
                            <?php } ?>
                            <li role="presentation"><?php echo modal_anchor(get_uri("invoices/modal_form"), "<i data-feather='copy' class='icon-16'></i> " . app_lang('clone_invoice'), array("data-post-is_clone" => true, "data-post-id" => $invoice_info->id, "title" => app_lang('clone_invoice'), "class" => "dropdown-item")); ?></li>
                        <?php } ?>

                    </ul>
                </span>
                <?php if ($invoice_status !== "cancelled" && $can_edit_invoices) { ?>
                    <?php echo modal_anchor(get_uri("invoices/item_modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_item'), array("class" => "btn btn-default", "title" => app_lang('add_item'), "data-post-invoice_id" => $invoice_info->id)); ?>
                    <?php echo modal_anchor(get_uri("invoice_payments/payment_modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_payment'), array("class" => "btn btn-default", "title" => app_lang('add_payment'), "data-post-invoice_id" => $invoice_info->id)); ?>
                <?php } ?>
            </div>
        </div>

        <div id="invoice-status-bar">
            <?php echo view("invoices/invoice_status_bar"); ?>
        </div>

        <?php
        if ($invoice_info->recurring) {
            echo view("invoices/invoice_recurring_info_bar");
        }
        ?>

        <div class="mt15">
            <div class="card p15 b-t">
                <div class="clearfix p20">
                    <!-- small font size is required to generate the pdf, overwrite that for screen -->
                    <style type="text/css"> .invoice-meta {font-size: 100% !important;}</style>

                    <?php
                    $color = get_setting("invoice_color");
                    if (!$color) {
                        $color = "#2AA384";
                    }
                    $invoice_style = get_setting("invoice_style");
                    $data = array(
                        "client_info" => $client_info,
                        "color" => $color,
                        "invoice_info" => $invoice_info
                    );

                    if ($invoice_style === "style_2") {
                        echo view('invoices/invoice_parts/header_style_2.php', $data);
                    } else {
                        echo view('invoices/invoice_parts/header_style_1.php', $data);
                    }
                    ?>
                </div>

                <div class="table-responsive mt15 pl15 pr15">
                    <table id="invoice-item-table" class="display" width="100%">            
                    </table>
                </div>

                <div class="clearfix">
                    <div class="float-end pr15" id="invoice-total-section">
                        <?php echo view("invoices/invoice_total_section", array("invoice_id" => $invoice_info->id, "can_edit_invoices" => $can_edit_invoices)); ?>
                    </div>
                </div>

                <?php
                $files = @unserialize($invoice_info->files);
                if ($files && is_array($files) && count($files)) {
                    ?>
                    <div class="clearfix">
                        <div class="col-md-12 mt20">
                            <p class="b-t"></p>
                            <div class="mb5 strong"><?php echo app_lang("files"); ?></div>
                            <?php
                            foreach ($files as $key => $value) {
                                $file_name = get_array_value($value, "file_name");
                                echo "<div>";
                                echo js_anchor(remove_file_prefix($file_name), array("data-toggle" => "app-modal", "data-sidebar" => "0", "data-url" => get_uri("invoices/file_preview/" . $invoice_info->id . "/" . $key)));
                                echo "</div>";
                            }
                            ?>
                        </div>
                    </div>
                <?php } ?>

                <p class="b-t b-info pt10 m15"><?php echo nl2br($invoice_info->note); ?></p>

            </div>
        </div>



        <?php if ($invoice_info->recurring) { ?>
            <ul id="invoice-view-tabs" data-bs-toggle="ajax-tab" class="nav nav-tabs" role="tablist">
                <li><a  role="presentation" href="#" data-bs-target="#invoice-payments"> <?php echo app_lang('payments'); ?></a></li>
                <li><a  role="presentation" href="<?php echo_uri("invoices/sub_invoices/" . $invoice_info->id); ?>" data-bs-target="#sub-invoices"> <?php echo app_lang('sub_invoices'); ?></a></li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade active" id="invoice-payments">
                    <div class="card">
                        <div class="tab-title clearfix">
                            <h4> <?php echo app_lang('invoice_payment_list'); ?></h4>
                        </div>
                        <div class="table-responsive">
                            <table id="invoice-payment-table" class="display" cellspacing="0" width="100%">            
                            </table>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="sub-invoices"></div>
            </div>
        <?php } else { ?>

            <div class="card">
                <div class="tab-title clearfix">
                    <h4> <?php echo app_lang('invoice_payment_list'); ?></h4>
                </div>
                <div class="table-responsive">
                    <table id="invoice-payment-table" class="display" cellspacing="0" width="100%">            
                    </table>
                </div>
            </div>
        <?php } ?>
    </div>
</div>



<script type="text/javascript">
    $(document).ready(function () {
        var optionVisibility = false;
        if ("<?php echo $can_edit_invoices ?>") {
            optionVisibility = true;
        }

        $("#invoice-item-table").appTable({
            source: '<?php echo_uri("invoices/item_list_data/" . $invoice_info->id . "/") ?>',
            order: [[0, "asc"]],
            hideTools: true,
            displayLength: 100,
            columns: [
                {visible: false, searchable: false},
                {title: '<?php echo app_lang("item") ?> ', "bSortable": false},
                {title: '<?php echo app_lang("quantity") ?>', "class": "text-right w15p", "bSortable": false},
                {title: '<?php echo app_lang("rate") ?>', "class": "text-right w15p", "bSortable": false},
                {title: '<?php echo app_lang("total") ?>', "class": "text-right w15p", "bSortable": false},
                {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100", "bSortable": false, visible: optionVisibility}
            ],
            onInitComplete: function () {
<?php if ($can_edit_invoices) { ?>
                    //apply sortable
                    $("#invoice-item-table").find("tbody").attr("id", "invoice-item-table-sortable");
                    var $selector = $("#invoice-item-table-sortable");

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
                                url: '<?php echo_uri("Invoices/update_item_sort_values") ?>',
                                type: "POST",
                                data: {sort_values: data},
                                success: function () {
                                    appLoader.hide();
                                }
                            });
                        }
                    });

<?php } ?>

            },
            onDeleteSuccess: function (result) {
                $("#invoice-total-section").html(result.invoice_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.invoice_id);
                }
            },
            onUndoSuccess: function (result) {
                $("#invoice-total-section").html(result.invoice_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.invoice_id);
                }
            }
        });

        $("#invoice-payment-table").appTable({
            source: '<?php echo_uri("invoice_payments/payment_list_data/" . $invoice_info->id . "/") ?>',
            order: [[0, "asc"]],
            columns: [
                {targets: [0], visible: false, searchable: false},
                {visible: false, searchable: false},
                {title: '<?php echo app_lang("payment_date") ?> ', "class": "w15p", "iDataSort": 1},
                {title: '<?php echo app_lang("payment_method") ?>', "class": "w15p"},
                {title: '<?php echo app_lang("note") ?>'},
                {title: '<?php echo app_lang("amount") ?>', "class": "text-right w15p"},
                {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100", visible: optionVisibility}
            ],
            onDeleteSuccess: function (result) {
                $("#invoice-total-section").html(result.invoice_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.invoice_id);
                }
            },
            onUndoSuccess: function (result) {
                $("#invoice-total-section").html(result.invoice_total_view);
                if (typeof updateInvoiceStatusBar == 'function') {
                    updateInvoiceStatusBar(result.invoice_id);
                }
            }
        });

        //modify the delete confirmation texts
        $("#confirmationModalTitle").html("<?php echo app_lang('cancel') . "?"; ?>");
        $("#confirmDeleteButton").html("<i data-feather='x' class='icon-16'></i> <?php echo app_lang("cancel"); ?>");
    });

    updateInvoiceStatusBar = function (invoiceId) {
        $.ajax({
            url: "<?php echo get_uri("invoices/get_invoice_status_bar"); ?>/" + invoiceId,
            success: function (result) {
                if (result) {
                    $("#invoice-status-bar").html(result);
                }
            }
        });
    };

    //print invoice
    $("#print-invoice-btn").click(function () {
        appLoader.show();

        $.ajax({
            url: "<?php echo get_uri('invoices/print_invoice/' . $invoice_info->id) ?>",
            dataType: 'json',
            success: function (result) {
                if (result.success) {
                    document.body.innerHTML = result.print_view; //add invoice's print view to the page
                    $("html").css({"overflow": "visible"});

                    setTimeout(function () {
                        window.print();
                    }, 200);
                } else {
                    appAlert.error(result.message);
                }

                appLoader.hide();
            }
        });
    });

    //reload page after finishing print action
    window.onafterprint = function () {
        location.reload();
    };

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

