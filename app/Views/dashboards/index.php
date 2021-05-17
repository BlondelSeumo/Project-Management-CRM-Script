<div id="page-content" class="page-wrapper clearfix">

    <?php
    if (count($dashboards)) {
        echo view("dashboards/dashboard_header");
    }

    echo announcements_alert_widget();
    ?>

    <div class="row">
        <?php
        $widget_column = "3"; //default bootstrap column class
        $total_hidden = 0;

        if (!$show_attendance) {
            $total_hidden += 1;
        }

        if (!$show_event) {
            $total_hidden += 1;
        }

        if (!$show_timeline) {
            $total_hidden += 1;
        }

        //set bootstrap class for column
        if ($total_hidden == 1) {
            $widget_column = "4";
        } else if ($total_hidden == 2) {
            $widget_column = "6";
        } else if ($total_hidden == 3) {
            $widget_column = "12";
        }
        ?>

        <?php if ($show_attendance) { ?>
            <div class="col-md-<?php echo $widget_column; ?> col-sm-6 widget-container">
                <?php
                echo clock_widget();
                ?>
            </div>
        <?php } ?>

        <div class="col-md-<?php echo $widget_column; ?> col-sm-6  widget-container">
            <?php
            echo my_open_tasks_widget();
            ?> 
        </div>

        <?php if ($show_event) { ?>
            <div class="col-md-<?php echo $widget_column; ?> col-sm-6  widget-container">
                <?php
                echo events_today_widget();
                ?> 
            </div>
        <?php } ?>

        <?php if ($show_timeline) { ?>
            <div class="col-md-<?php echo $widget_column; ?> col-sm-6  widget-container">
                <?php
                echo new_posts_widget();
                ?>  
            </div>
        <?php } ?>

    </div>

    <div class="row">
        <div class="col-md-5">

            <?php if ($show_projects_count || $show_clock_status || $show_total_hours_worked || $show_total_project_hours) { ?>
                <div class="row">
                    <div class="col-md-12 mb20 text-center">
                        <div class="bg-white project_and_clock_status_widget">
                            <?php
                            if ($show_projects_count) {
                                echo count_project_status_widget();
                            }

                            if ($show_clock_status) {
                                echo count_clock_status_widget();
                            } else {
                                echo count_total_time_widget();
                            }
                            ?> 
                        </div>
                    </div>
                </div>
            <?php } ?>

            <div class="row">
                <div class="col-md-12">
                    <?php
                    if ($show_invoice_statistics) {
                        echo invoice_statistics_widget();
                    } else if ($show_project_timesheet) {
                        if ($login_user->is_admin) {
                            echo project_timesheet_statistics_widget("all_timesheet_statistics");
                        } else {
                            echo project_timesheet_statistics_widget("my_timesheet_statistics");
                        }
                    }
                    ?> 
                </div>
            </div>


            <div class="row">
                <div class="col-md-12">
                    <?php
                    if ($show_ticket_status) {
                        echo ticket_status_widget();
                    } else if ($show_attendance) {
                        echo timecard_statistics_widget();
                    }
                    ?>                        
                </div>
            </div>

        </div>

        <div class="col-md-4 widget-container">
            <div class="card bg-white">
                <div class="card-header">
                    <i data-feather="clock" class="icon-16"></i>&nbsp;  <?php echo app_lang("project_timeline"); ?>
                </div>
                <div id="project-timeline-container">
                    <div class="card-body"> 
                        <?php
                        echo activity_logs_widget(array("log_for" => "project", "limit" => 10));
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="row">
                <div class="col-md-12 widget-container">
                    <?php
                    if ($show_income_vs_expenses) {
                        echo income_vs_expenses_widget();
                    } else {
                        echo my_task_stataus_widget();
                    }
                    ?>
                </div>
            </div>

            <?php if ($show_event) { ?>
                <div class="row">
                    <div class="col-md-12 widget-container">
                        <?php echo events_widget(); ?>
                    </div>
                </div>
            <?php } ?>

            <div class="row">
                <div class="col-md-12 widget-container">
                    <?php echo sticky_note_widget(); ?>
                </div>
            </div>
        </div>

    </div>

</div>
<script type="text/javascript">
    $(document).ready(function () {
        initScrollbar('#project-timeline-container', {
            setHeight: 965
        });

        //update dashboard link
        $(".dashboard-menu, .dashboard-image").closest("a").attr("href", window.location.href);

    });
</script>    

