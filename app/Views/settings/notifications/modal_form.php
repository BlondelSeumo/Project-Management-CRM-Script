<?php echo form_open(get_uri("settings/save_notification_settings"), array("id" => "notification-settings-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
        <div class="form-group">
            <div class="row">
                <label for="title" class=" col-md-3"><strong><?php echo app_lang('event'); ?></strong></label>
                <div class=" col-md-9">
                    <strong>
                        <?php
                        echo app_lang($model_info->event);
                        ?>
                    </strong>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="enable_email" class="col-md-3"><?php echo app_lang('enable_email'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_checkbox("enable_email", "1", $model_info->enable_email ? true : false, "id='enable_email' class='form-check-input'");
                    ?>                       
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="enable_web" class="col-md-3"><?php echo app_lang('enable_web'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_checkbox("enable_web", "1", $model_info->enable_web ? true : false, "id='enable_web' class='form-check-input'");
                    ?>                       
                </div>
            </div>
        </div>

        <?php if ($model_info->event !== "new_message_sent" && $model_info->event !== "message_reply_sent") { ?>
            <div class="form-group">
                <div class="row">
                    <label for="enable_slack" class="col-md-3"><?php echo app_lang('enable_slack'); ?></label>
                    <div class="col-md-9">
                        <?php
                        echo form_checkbox("enable_slack", "1", $model_info->enable_slack ? true : false, "id='enable_slack' class='form-check-input'");
                        ?>                       
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="form-group">
            <div class="row">
                <label for="notify_to" class="col-md-3"><?php echo app_lang('notify_to'); ?></label>
                <div class="col-md-9">

                    <?php
                    foreach ($notify_to as $notify_optoin) {
                        if ($notify_optoin === "team") {
                            ?>
                            <div class="pb10">
                                <label for="notify_to_team"><?php echo app_lang('team'); ?></label>
                                <div>
                                    <input type="text" value="<?php echo $model_info->notify_to_team; ?>" name="team" id="team_dropdown" class="w100p"  placeholder="<?php echo app_lang('team'); ?>"  />    
                                </div>
                            </div>
                        <?php } else if ($notify_optoin === "team_members") { ?>
                            <div class="pb10">
                                <label for="notify_to_team_members"><?php echo app_lang('team_members'); ?></label>
                                <div>
                                    <input type="text" value="<?php echo $model_info->notify_to_team_members; ?>" name="team_members" id="team_members_dropdown" class="w100p"  placeholder="<?php echo app_lang('team_members'); ?>"  />    
                                </div>
                            </div>
                        <?php } else {
                            ?>
                            <div class="pb10">
                                <?php
                                $selected = false;
                                if (in_array($notify_optoin, $model_info->notify_to_terms)) {
                                    $selected = true;
                                }

                                echo form_checkbox($notify_optoin, "1", $selected ? true : false, "id='$notify_optoin' class='form-check-input'");
                                echo "<label for='$notify_optoin'>" . app_lang($notify_optoin) . "</label>";
                                ?>
                            </div>

                            <?php
                        }
                    }
                    ?>

                </div>
            </div>
        </div>

    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#notification-settings-form").appForm({
            onSuccess: function (result) {
                $("#notification-settings-table").appTable({newData: result.data, dataId: result.id});
                console.log(result.data);
            }
        });


        $("#team_members_dropdown").select2({
            multiple: true,
            data: <?php echo ($members_dropdown); ?>
        });

        $("#team_dropdown").select2({
            multiple: true,
            data: <?php echo ($team_dropdown); ?>
        });

    });
</script>    