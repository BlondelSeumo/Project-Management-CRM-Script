<div class="card bg-white">
    <div class="card-header">
        <i data-feather="life-buoy" class="icon-16"></i>&nbsp;<?php echo app_lang("ticket_status"); ?>
    </div>
    <div class="card-body rounded-bottom">
        <canvas id="ticket-status-chart" style="width: 100%; height: 300px;"></canvas>
    </div>
</div>

<?php if ($new || $open || $closed) { ?>
    <script type="text/javascript">
        var ticketStatusChart = document.getElementById("ticket-status-chart");
        new Chart(ticketStatusChart, {
            type: 'doughnut',
            data: {
                labels: ["<?php echo app_lang("new"); ?>", "<?php echo app_lang("open"); ?>", "<?php echo app_lang("closed"); ?>"],
                datasets: [
                    {
                        data: ["<?php echo $new ?>", "<?php echo $open ?>", "<?php echo $closed ?>"],
                        backgroundColor: ["#F0AD4E", "#F06C71", "#00B393"],
                        borderWidth: 0
                    }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                tooltips: {
                    callbacks: {
                        afterLabel: function (tooltipItem, data) {
                            var dataset = data['datasets'][0];
                            var percent = Math.round((dataset['data'][tooltipItem['index']] / dataset["_meta"][Object.keys(dataset["_meta"])[0]]['total']) * 100);
                            return '(' + percent + '%)';
                        }
                    }
                },
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        fontColor: "#898fa9"
                    }
                }
            }
        });
    </script>    
<?php } ?>