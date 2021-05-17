<div class="card">
    <div class="card-header clearfix border-bottom-0">
        <div class="float-start strong"><i data-feather="bar-chart-2" class="icon-16"></i>&nbsp; <?php echo app_lang("chart"); ?></div>
        <div class="float-end clearfix">
            <span id="monthly-chart-date-range-selector" class="float-end"></span>
            <?php
            echo form_input(array(
                "id" => "members-dropdown",
                "name" => "members-dropdown",
                "class" => "select2 w200 reload-timesheet-chart float-end",
                "placeholder" => app_lang('member')
            ));
            ?>
            <?php
            if (!$project_id) {
                echo form_input(array(
                    "id" => "projects-dropdown",
                    "name" => "projects-dropdown",
                    "class" => "select2 w200 reload-timesheet-chart float-end mr15",
                    "placeholder" => app_lang('project')
                ));
            }
            ?>
        </div>
    </div>
    <div class="card-body">
        <canvas id="timesheet-statistics-chart" style="width: 100%; height: 350px;"></canvas>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var date = {};

        //initialize data
        $("#members-dropdown").select2({data: <?php echo $members_dropdown; ?>});
        $("#projects-dropdown").select2({data: <?php echo $projects_dropdown; ?>});

        //prepare timesheet statistics Chart
        prepareTimesheetStatisticsChart = function () {
            appLoader.show();

            var user_id = $("#members-dropdown").val() || "0", project_id = $("#projects-dropdown").val() || "0";

            $.ajax({
                url: "<?php echo_uri("projects/timesheet_chart_data/$project_id") ?>",
                data: {start_date: date.start_date, end_date: date.end_date, user_id: user_id, project_id: project_id},
                cache: false,
                type: 'POST',
                dataType: "json",
                success: function (response) {
                    appLoader.hide();
                    initTimesheetStatisticsChart(response.timesheets, response.ticks);
                }
            });
        };

        $("#monthly-chart-date-range-selector").appDateRange({
            dateRangeType: "monthly",
            onChange: function (dateRange) {
                date = dateRange;
                prepareTimesheetStatisticsChart();
            },
            onInit: function (dateRange) {
                date = dateRange;
                prepareTimesheetStatisticsChart();
            }
        });

        $(".reload-timesheet-chart").change(function () {
            prepareTimesheetStatisticsChart();
        });

    });

    var timesheetStatisticsContent;

    initTimesheetStatisticsChart = function (timesheets, ticks) {
        var timesheetStatistics = document.getElementById("timesheet-statistics-chart");

        if (timesheetStatisticsContent) {
            timesheetStatisticsContent.destroy();
        }

        timesheetStatisticsContent = new Chart(timesheetStatistics, {
            type: 'line',
            data: {
                labels: ticks,
                datasets: [{
                        label: '<?php echo app_lang("timesheets"); ?>',
                        data: timesheets,
                        fill: true,
                        borderColor: '#2196f3',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
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
                    xAxes: [
                        {
                            ticks: {
                                autoSkip: true,
                                fontColor: "#898fa9"
                            },
                            gridLines: {
                                color: 'rgba(107, 115, 148, 0.1)'
                            }
                        }
                    ],
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
    };
</script>    

