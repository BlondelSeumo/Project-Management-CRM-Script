<div id="invoice-payment-statistics-container">
    <?php echo view("invoices/invoice_statistics_widget/widget_data"); ?>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $(".load-currency-wise-data").click(function () {
            var currencyValue = $(this).attr("data-value");

            $.ajax({
                url: "<?php echo get_uri('invoices/load_statistics_of_selected_currency') ?>" + "/" + currencyValue,
                type: 'POST',
                dataType: 'json',
                success: function (result) {
                    if (result.success) {
                        $("#invoice-payment-statistics-container").html(result.statistics);
                    }
                }
            });
        });
    });
</script>    

