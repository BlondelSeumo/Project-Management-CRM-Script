<div class="card">
    <div class="card-header clearfix border-bottom-0">
        <div class="float-start strong"><i data-feather="bar-chart-2" class="icon-16"></i>&nbsp; <?php echo app_lang("chart"); ?></div>
        <div class="float-end">
            <?php
            if ($currencies_dropdown) {
                echo form_input(array(
                    "id" => "payment-chart-currency-dropdown",
                    "name" => "payment-chart-currency-dropdown",
                    "class" => "select2 w200 font-normal",
                    "placeholder" => app_lang('currency')
                ));
            }
            ?>

            <div id="payment-chart-date-range-selector" class="inline-block"></div>

        </div>
    </div>
    <div class="card-body ">
        <canvas id="yearly-payment-chart" style="width:100%; height: 350px;"></canvas>
    </div>
</div>


<script type="text/javascript">
    var preparePaymentsChart = function (data, currency) {
        data["currency"] = currency;

        appLoader.show();
        $.ajax({
            url: "<?php echo_uri("invoice_payments/yearly_chart_data") ?>",
            data: data,
            cache: false,
            type: 'POST',
            dataType: "json",
            success: function (response) {
                appLoader.hide();
                initPaymentsChart(response.months, response.data, response.currency_symbol);
            }
        });

    };

    var yearlyPaymentChartContent;

    var initPaymentsChart = function (months, data, currency_symbol) {
        // var months = ﻿["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        // var data = ["1000", "0", "1200", "0", "600", "500", "0", "0", "0", "1700", "0", "0"],

        var yearlyPaymentChart = document.getElementById("yearly-payment-chart");

        if (yearlyPaymentChartContent) {
            yearlyPaymentChartContent.destroy();
        }

        yearlyPaymentChartContent = new Chart(yearlyPaymentChart, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                        label: '',
                        data: data,
                        borderColor: '#36a2eb',
                        backgroundColor: 'rgba(54, 162, 235, 0.3)',
                        borderWidth: 1
                    }]},
            options: {
                responsive: true,
                tooltips: {
                    callbacks: {
                        label: function (tooltipItem, data) {
                            return toCurrency((data['datasets'][0]['data'][tooltipItem['index']]), currency_symbol);
                        }
                    }
                },
                legend: {
                    display: true,
                    position: 'bottom'
                },
                scales: {
                    xAxes: [{
                            gridLines: {
                                color: 'rgba(127,127,127,0.1)'
                            },
                            ticks: {
                                fontColor: "#898fa9"
                            }
                        }],
                    yAxes: [{
                            gridLines: {
                                color: 'rgba(127,127,127,0.1)'
                            },
                            ticks: {
                                fontColor: "#898fa9"
                            }
                        }]
                }
            }
        });
    };

    $(document).ready(function () {
        var date = {}, currency = "";

        $("#payment-chart-date-range-selector").appDateRange({
            dateRangeType: "yearly",
            onChange: function (dateRange) {
                date = dateRange;
                preparePaymentsChart(dateRange, currency);
            },
            onInit: function (dateRange) {
                date = dateRange;
                preparePaymentsChart(dateRange, currency);
            }
        });

        var $currenciesDropdown = $("#payment-chart-currency-dropdown");

<?php if ($currencies_dropdown) { ?>
            $currenciesDropdown.select2({data: <?php echo $currencies_dropdown; ?>});
<?php } ?>

        $currenciesDropdown.change(function () {
            currency = $currenciesDropdown.val();
            preparePaymentsChart(date, currency);
        });
    });
</script>