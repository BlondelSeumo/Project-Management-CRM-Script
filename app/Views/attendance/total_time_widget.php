<div class="box <?php echo ($show_projects_count && ($show_total_hours_worked || $show_total_project_hours)) ? 'border-top' : ''; ?>">

    <?php if ($show_total_hours_worked) { ?>
        <div class="box-content <?php echo $show_total_project_hours ? 'border-end' : ''; ?>">
            <div class="card-body ">
                <h1><?php echo $total_hours_worked; ?></h1>
                <span class="text-off uppercase"><?php echo app_lang("total_hours_worked"); ?></span>
            </div>
        </div>
    <?php } ?>

    <?php if ($show_total_project_hours) { ?>
        <div class="box-content">
            <div class="card-body ">
                <h1 class=""><?php echo $total_project_hours; ?></h1>
                <span class="text-off uppercase"><?php echo app_lang("total_project_hours"); ?></span>
            </div>
        </div>
    <?php } ?>

</div>