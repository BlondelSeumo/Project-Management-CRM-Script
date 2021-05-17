<div id="page-content" class="page-wrapper clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "dashboard";
            echo view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">

            <div class="card">
                <div class="page-title clearfix">
                    <h4><?php echo app_lang("dashboard"); ?></h4>
                    <div class="title-button-group">
                        <button id="restore_to_default" data-bs-toggle="popover" data-placement="bottom" type="button" class="btn btn-danger"><span data-feather="refresh-cw" class="icon-16"></span> <?php echo app_lang('restore_to_default'); ?></button>
                        <?php echo anchor(get_uri("dashboard/edit_client_default_dashboard"), "<i data-feather='columns' class='icon-16'></i> " . app_lang('edit_dashboard'), array("class" => "btn btn-default", "title" => app_lang('edit_dashboard'))); ?>
                    </div>
                </div>

                <div class="bg-off-white">
                    <div class="client-dashboard-help-message"><?php echo app_lang("client_dashboard_help_message"); ?></div>

                    <?php echo $dashboard_view; ?>

                </div>

            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#restore_to_default').click(function () {
            $(this).appConfirmation({
                title: "<?php echo app_lang('are_you_sure'); ?>",
                btnConfirmLabel: "<?php echo app_lang('yes'); ?>",
                btnCancelLabel: "<?php echo app_lang('no'); ?>",
                onConfirm: function () {
                    $.ajax({
                        url: "<?php echo get_uri('dashboard/restore_to_default_client_dashboard') ?>",
                        type: 'POST',
                        success: function () {
                            location.reload();
                        }
                    });

                }
            });

            return false;
        });
    });
</script>  