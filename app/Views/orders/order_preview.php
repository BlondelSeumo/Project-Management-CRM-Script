<div id="page-content" class="page-wrapper clearfix">
    <?php
    load_css(array(
        "assets/css/invoice.css",
    ));
    ?>

    <div class="invoice-preview">
        <?php
        if ($login_user->user_type === "client") {
            echo "<div class='text-center'>" . anchor("orders/download_pdf/" . $order_info->id, app_lang("download_pdf"), array("class" => "btn btn-default round")) . "</div>";
        }

        if ($show_close_preview) {
            echo "<div class='text-center'>" . anchor("orders/view/" . $order_info->id, app_lang("close_preview"), array("class" => "btn btn-default round")) . "</div>";
        }
        ?>

        <div class="invoice-preview-container bg-white mt15">
            <div class="row">
                <div class="col-md-12 position-relative">
                    <div class="ribbon"><?php echo "<span class='mt0 badge large' style='background-color: $order_info->order_status_color'>$order_info->order_status_title</span>"; ?></div>
                </div>
            </div>

            <?php
            echo $order_preview;
            ?>
        </div>

    </div>
</div>
