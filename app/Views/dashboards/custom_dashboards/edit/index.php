<div id="page-content" class="page-wrapper clearfix">

    <div class="clearfix">
        <div class="p15 pt0 pl0" id="widget-container-area">
            <?php echo view("dashboards/custom_dashboards/edit/widgets") ?>
        </div>

        <div class="p15 pt0 pr0 pl0" id="widget-row-container">
            <div class="card">

                <?php echo form_open(get_uri("dashboard/save"), array("id" => "dashboard-form", "class" => "general-form", "role" => "form")); ?>

                <input type="hidden" name="data" id="widgets-data" value=""/>
                <input type="hidden" name="id" value="<?php echo $dashboard_info->id; ?>" />
                <input type="hidden" name="title" value="<?php echo $dashboard_info->title; ?>" />
                <input type="hidden" name="color" value="<?php echo $dashboard_info->color; ?>" />

                <div class="page-title clearfix">
                    <h4><?php echo $dashboard_info->title; ?></h4>

                    <div class="title-button-group">
                        <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang("save"); ?></button>
                        <button id="save-and-show-button" class="btn btn-info text-white"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang("save_and_show"); ?></button>
                    </div>
                </div>

                <div class="card-body clearfix">
                    <div class="col-md-12 p15 bg-off-white float-end" id="widget-row-area">
                        <?php echo view("dashboards/custom_dashboards/edit/dashboard_rows") ?>
                    </div>

                </div>
                <?php echo form_close(); ?>

            </div>
        </div>
    </div>

</div>

<?php echo view("dashboards/helper_js"); ?>

<script>
    $(document).ready(function () {

        var hasRows = <?php
if ($widget_sortable_rows) {
    echo 1;
} else {
    echo 0;
}
?>;

        if (hasRows) {
            //initialize sortable if it's edit mode and there are widgets in dashboard
            initSortable();
            $("#widget-row-container").addClass("ml298");
        } else {
            //show the add row button in full width and initialize the functionable class to the collapse panel
            $("#widget-container-area").addClass("hide");
            $("#add-column-collapse-panel").addClass("first-row-of-widget");
        }

        $("#dashboard-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });

        //save and show
        $("#save-and-show-button").click(function () {
            $(this).trigger("submit");

            setTimeout(function () {
                window.location = "<?php echo get_uri("dashboard/view/" . $dashboard_info->id); ?>";
            }, 300);
        });

        //in edit mode, store the existing data to input field
        saveWidgetPosition();

        adjustHeightOfWidgetContainer();

        $(window).resize(function () {
            adjustHeightOfWidgetContainer();
        });

    });

</script>