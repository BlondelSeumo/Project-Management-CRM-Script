<div class="card bg-white">
    <div class="card-header">
        <i data-feather="clock" class="icon-16"></i>&nbsp; <?php echo app_lang("timecard_statistics"); ?>
    </div>
    <div class="card-body rounded-bottom">
        <canvas id="timecard-statistics-chart" style="width: 100%; height: 300px;"></canvas>
    </div>
</div>

<script type="text/javascript">
    var timecardStatisticsChart = document.getElementById("timecard-statistics-chart");

    var timecards = <?php echo $timecards; ?>;
    var ticks = <?php echo $ticks; ?>;

    new Chart(timecardStatisticsChart, {
        type: 'line',
        data: {
            labels: ticks,
            datasets: [{
                    label: 'Time cards',
                    data: timecards,
                    fill: true,
                    borderColor: 'rgba(0, 179, 147, 1)',
                    backgroundColor: 'rgba(0, 179, 147, 0.2)',
                    borderWidth: 2
                }]},
        options: {
            responsive: true,
            tooltips: {
                callbacks: {
                    title: function (tooltipItem, data) {
                        return data['labels'][tooltipItem[0]['index']];
                    },
                    label: function (tooltipItem, data) {
                        return secondsToTimeFormat(data['datasets'][0]['data'][tooltipItem['index']] * 60);
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
</script>