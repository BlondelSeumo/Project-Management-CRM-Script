<div id="page-content" class="page-wrapper clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "client_projects";
            echo view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <div class="card">
                <div class="page-title clearfix">
                    <h4> <?php echo app_lang('projects'); ?></h4>
                </div>

                <?php echo form_open(get_uri("settings/save_client_project_settings"), array("id" => "project-settings-form", "class" => "general-form dashed-row", "role" => "form")); ?>
                <div class="card-body">
                    <div class="form-group">
                        <div class="row">
                            <label for="project_tab_order_of_clients" class=" col-md-2"><?php echo app_lang('set_project_tab_order'); ?></label>
                            <div class=" col-md-9">
                                <?php
                                echo form_input(array(
                                    "id" => "project_tab_order_of_clients",
                                    "name" => "project_tab_order_of_clients",
                                    "value" => get_setting("project_tab_order_of_clients"),
                                    "class" => "form-control",
                                    "placeholder" => app_lang('project_tab_order')
                                ));
                                ?>
                                <span class="mt10 d-inline-block text-off"><i data-feather='info' class="icon-14"></i> <?php echo app_lang("project_tab_order_help_message_of_client"); ?></span> 
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><span data-feather='check-circle' class="icon-16"></span> <?php echo app_lang('save'); ?></button>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#project-settings-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });

        $("#project_tab_order_of_clients").select2({
            multiple: true,
            data: <?php echo ($project_tabs_dropdown); ?>
        });

        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>