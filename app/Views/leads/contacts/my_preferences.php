<div class="tab-content">
    <?php
    $user_id = $login_user->id;
    echo form_open(get_uri("leads/save_my_preferences/"), array("id" => "my-preferences-form", "class" => "general-form dashed-row white", "role" => "form"));
    ?>
    <div class="card">
        <div class=" card-header">
            <h4> <?php echo app_lang('my_preference'); ?></h4>
        </div>
        <div class="card-body">
            <div class="form-group">
                <div class="row">
                    <label for="show_push_notification" class=" col-md-2"><?php echo app_lang('show_push_notification'); ?></label>
                    <div class=" col-md-10">
                        <?php
                        echo form_dropdown(
                                "show_push_notification", array(
                            "no" => app_lang("no"),
                            "yes" => app_lang("yes")
                                ), get_setting('user_' . $user_id . '_show_push_notification'), "class='select2 mini'"
                        );
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="notification_sound_volume" class=" col-md-2"><?php echo app_lang('notification_sound_volume'); ?></label>
                    <div class=" col-md-10">
                        <?php
                        echo form_dropdown(
                                "notification_sound_volume", array(
                            "0" => "-",
                            "1" => "|",
                            "2" => "||",
                            "3" => "|||",
                            "4" => "||||",
                            "5" => "|||||",
                            "6" => "||||||",
                            "7" => "|||||||",
                            "8" => "||||||||",
                            "9" => "|||||||||",
                                ), get_setting('user_' . $user_id . '_notification_sound_volume'), "class='select2 mini'"
                        );
                        ?>
                    </div>
                </div>
            </div>

        </div>
        <div class="card-footer rounded-0">
            <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#my-preferences-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });
        $("#my-preferences-form .select2").select2();

    });
</script>    