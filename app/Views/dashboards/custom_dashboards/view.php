<div id="page-content" class="page-wrapper clearfix dashboard-view">

    <?php
    if (count($dashboards)) {
        echo view("dashboards/dashboard_header");
    }
    ?>

    <div class="clearfix row">
        <div class="col-md-12 widget-container">
            <?php echo announcements_alert_widget(); ?>
        </div>
    </div>

    <?php
    if ($widget_columns) {
        echo $widget_columns;
    } else {
        echo view("dashboards/custom_dashboards/no_widgets");
    }
    ?>

</div>

<?php echo view("dashboards/helper_js"); ?>

<script>
    $(document).ready(function () {
        //we have to reload the same page when editting title
        $("#dashboard-edit-title-button").click(function () {
            window.dashboardTitleEditMode = true;
        });

        //update dashboard link
        $(".dashboard-menu, .dashboard-image").closest("a").attr("href", window.location.href);

        onDashboardDeleteSuccess = function (result, $selector) {
            window.location.href = "<?php echo get_uri("dashboard"); ?>";
        };

        initScrollbar('#project-timeline-container', {
            setHeight: 719
        });

        initScrollbar('#upcoming-event-container', {
            setHeight: 330
        });

        initScrollbar('#client-projects-list', {
            setHeight: 316
        });

    });
</script>