<div class="clearfix default-bg">

    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <?php echo view("projects/project_progress_chart_info"); ?>
                </div>
                <div class="col-md-6 col-sm-12">
                    <?php echo view("projects/project_task_pie_chart"); ?>
                </div>

                <?php if (get_setting('module_project_timesheet')) { ?>
                    <div class="col-md-12 col-sm-12">
                        <?php echo view("projects/widgets/total_hours_worked_widget"); ?>
                    </div>
                <?php } ?>

                <div class="col-md-12 col-sm-12 project-custom-fields">
                    <?php echo view('projects/custom_fields_list', array("custom_fields_list" => $custom_fields_list)); ?>
                </div>

                <?php if ($project_info->estimate_id) { ?>
                    <div class="col-md-12 col-sm-12">
                        <?php echo view("projects/estimates/index"); ?>
                    </div> 
                <?php } ?>

                <?php if ($can_add_remove_project_members) { ?>
                    <div class="col-md-12 col-sm-12">
                        <?php echo view("projects/project_members/index"); ?>
                    </div>  
                <?php } ?>

                <?php if ($can_access_clients) { ?>
                    <div class="col-md-12 col-sm-12">
                        <?php echo view("projects/client_contacts/index"); ?>
                    </div>  
                <?php } ?>

                <div class="col-md-12 col-sm-12">
                    <?php echo view("projects/project_description"); ?>
                </div>

            </div>
        </div>
        <div class="col-md-6">
            <div class="card project-activity-section">
                <div class="card-header">
                    <h4><?php echo app_lang('activity'); ?></h4>
                </div>
                <?php echo view("projects/history/index"); ?>
            </div>
        </div>
    </div>
</div>