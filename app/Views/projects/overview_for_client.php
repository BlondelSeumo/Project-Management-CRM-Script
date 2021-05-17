<div class="clearfix bg-white">

    <div class="row project-overview-for-client">
        <div class="col-md-12">
            <div class="row">
                <?php if ($show_overview) { ?>
                    <div class="col-md-6 col-sm-12">
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <?php echo view("projects/project_progress_chart_info"); ?>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <?php echo view("projects/project_task_pie_chart"); ?>
                            </div>

                            <?php if ($show_activity) { ?>
                                <div class="col-md-12 col-sm-12">
                                    <?php echo view('projects/custom_fields_list', array("custom_fields_list" => $custom_fields_list)); ?>
                                </div>

                                <div class="col-md-12 col-sm-12">
                                    <?php echo view("projects/project_description"); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <?php if (!$show_activity) { ?>
                        <div class="col-md-6 col-sm-12">
                            <?php echo view('projects/custom_fields_list', array("custom_fields_list" => $custom_fields_list)); ?>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <?php echo view("projects/project_description"); ?>
                        </div>
                    <?php } ?>

                    <?php if ($show_activity) { ?>
                        <div class="col-md-6 col-sm-12">
                            <div class="card">
                                <div class="tab-title clearfix">
                                    <h4><?php echo app_lang('activity'); ?></h4>
                                </div>
                                <?php echo view("projects/history/index"); ?>
                            </div>
                        </div>
                    <?php } ?>

                <?php } else { ?>
                    <div class="col-md-12">
                        <?php echo view('projects/custom_fields_list', array("custom_fields_list" => $custom_fields_list)); ?>
                    </div>

                    <div class="col-md-12">
                        <?php echo view("projects/project_description"); ?>
                    </div>

                <?php } ?>

            </div>
        </div>
    </div>
</div>