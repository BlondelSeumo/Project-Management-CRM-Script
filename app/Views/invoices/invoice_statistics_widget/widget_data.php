<div class="card bg-white">
    <div class="card-header clearfix">
        <i data-feather="file-text" class="icon-16"></i>&nbsp; <?php echo app_lang("invoice_statistics"); ?>

        <?php if ($currencies && $login_user->user_type == "staff") { ?>
            <div class="float-end">
                <span class="float-end dropdown">
                    <div class="dropdown-toggle clickable" type="button" data-bs-toggle="dropdown" aria-expanded="true" >
                        <span class="ml10 mr10"><i data-feather="more-horizontal" class="icon"></i></span>
                    </div>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <?php
                            $default_currency = get_setting("default_currency");
                            echo js_anchor($default_currency, array("class" => "dropdown-item load-currency-wise-data", "data-value" => $default_currency)); //default currency

                            foreach ($currencies as $currency) {
                                echo js_anchor($currency->currency, array("class" => "dropdown-item load-currency-wise-data", "data-value" => $currency->currency));
                            }
                            ?>
                        </li>
                    </ul>
                </span>
            </div>
        <?php } ?>
    </div>
    <div class="card-body rounded-bottom">
        <canvas id="invoice-payment-statistics-chart" style="width: 100%; height: 300px;"></canvas>
    </div>

</div>

<script type="text/javascript">
    $(document).ready(function () {
        var invoicePaymentChart = document.getElementById("invoice-payment-statistics-chart");
        new Chart(invoicePaymentChart, {
            type: 'line',
            data: {
                labels: ["<?php echo app_lang('short_january'); ?>", "<?php echo app_lang('short_february'); ?>", "<?php echo app_lang('short_march'); ?>", "<?php echo app_lang('short_april'); ?>", "<?php echo app_lang('short_may'); ?>", "<?php echo app_lang('short_june'); ?>", "<?php echo app_lang('short_july'); ?>", "<?php echo app_lang('short_august'); ?>", "<?php echo app_lang('short_september'); ?>", "<?php echo app_lang('short_october'); ?>", "<?php echo app_lang('short_november'); ?>", "<?php echo app_lang('short_december'); ?>"],
                datasets: [{
                        label: "<?php echo app_lang('payments'); ?>",
                        borderColor: 'rgba(0, 179, 147, 1)',
                        backgroundColor: 'rgba(0, 179, 147, 0.2)',
                        borderWidth: 2,
                        fill: true,
                        data: <?php echo $payments; ?>
                    }, {
                        label: "<?php echo app_lang('invoices'); ?>",
                        borderColor: 'rgba(137, 143, 169, 0.3)',
                        backgroundColor: 'rgba(137, 143, 169, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        data: <?php echo $invoices; ?>
                    }]
            },
            options: {
                responsive: true,
                tooltips: {
                    enabled: true,
                    mode: 'single',
                    callbacks: {
                        label: function (tooltipItems, data) {
                            if (tooltipItems) {
                                return data.datasets[tooltipItems.datasetIndex].label + ": " + toCurrency(tooltipItems.yLabel, "<?php echo $currency_symbol; ?>");
                            } else {
                                return false;
                            }
                        }
                    }
                },
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        fontColor: "#898fa9"
                    }
                },
                scales: {
                    xAxes: [{
                            gridLines: {
                                color: 'rgba(107, 115, 148, 0.1)'
                            },
                            ticks: {
                                fontColor: "#898fa9"
                            }
                        }],
                    yAxes: [{
                            gridLines: {
                                color: 'rgba(107, 115, 148, 0.1)'
                            },
                            ticks: {
                                fontColor: "#898fa9"
                            }
                        }]
                }
            }
        });
    });
</script>