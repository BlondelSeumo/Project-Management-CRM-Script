<div class="card <?php echo $custom_class; ?> bg-white">
    <div class="card-header">
        <i data-feather="list" class="icon-16"></i>&nbsp;<?php echo app_lang("task_status"); ?>
    </div>
    <div class="card-body rounded-bottom">
        <canvas id="my-task-status-pai" style="width: 100%; height: 300px;"></canvas>
    </div>
</div>
<?php
$task_title = array();
$task_data = array();
$task_status_color = array();
foreach ($task_statuses as $status) {
    $task_title[] = $status->title;
    $task_data[] = $status->total;
    $task_status_color[] = $status->color;
}
?>
<script type="text/javascript">
    //for task status chart
    var labels = <?php echo json_encode($task_title) ?>;
    var taskData = <?php echo json_encode($task_data) ?>;
    var taskStatuscolor = <?php echo json_encode($task_status_color) ?>;
    var myTaskStatusPie = document.getElementById("my-task-status-pai");

    new Chart(myTaskStatusPie, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [
                {
                    data: taskData,
                    backgroundColor: taskStatuscolor,
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
            },
            animation: {
                animateScale: true
            }
        }
    });
</script>