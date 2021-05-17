<div class="modal-body">
    <div class="container-fluid">
        <div class="row mb15">
            <div class="col-md-12 clearfix">
                <h4 class="mt0 float-start">
                    <?php
                    $share_title = app_lang("share_with") . ": ";
                    if (!$model_info->share_with) {
                        $share_title .= app_lang("only_me");
                    } else if ($model_info->share_with == "all") {
                        $share_title .= app_lang("all_team_members");
                    } else {
                        $share_title .= app_lang("specific_members_and_teams");
                    }

                    echo "<span title='$share_title' style='color:" . $model_info->color . "' class='float-start mr10'><i data-feather='$event_icon' class='icon-16'></i></span> " . $model_info->title;
                    ?>
                </h4>

                <?php if ($model_info->google_event_id) { ?>
                    <div class="float-end pb10 ">
                        <i data-feather="external-link" class="icon-16"></i>
                        <?php echo anchor(get_uri("events/show_event_in_google_calendar/$model_info->google_event_id"), app_lang("open_in_google_calendar"), array("target" => "_blank")); ?>
                    </div>
                <?php } ?>

            </div>

            <?php if ($status) { ?>
                <div class="col-md-12 pb10">
                    <?php echo $status; ?>
                </div>
            <?php } ?>

            <div class="col-md-12 pb10 ">
                <i data-feather="clock" class="icon-16"></i>
                <?php
                echo view("events/event_time");
                ?>
            </div>

            <div class="col-md-12 pb10">
                <?php echo $labels; ?>
            </div>

            <?php if ($model_info->description) { ?>
                <div class="col-md-12">
                    <blockquote class="font-14 text-justify" style="<?php echo "border-color:" . $model_info->color; ?>"><?php echo nl2br($model_info->description); ?></blockquote>
                </div>
            <?php } ?>

            <?php if ($model_info->company_name && $login_user->user_type != "client") { ?>
                <div class="col-md-12 pb10 pt10 ">
                    <i data-feather="<?php echo $model_info->is_lead ? "box" : "briefcase"; ?>" class="icon-16"></i>
                    <?php
                    echo $model_info->is_lead ? anchor("leads/view/" . $model_info->client_id, $model_info->company_name) : anchor("clients/view/" . $model_info->client_id, $model_info->company_name);
                    ?>
                </div>
            <?php } ?>

            <?php if ($model_info->location) { ?>
                <div class="col-md-12 mt5">
                    <div class="font-14"><i data-feather="map-pin" class="icon-16"></i> <?php echo nl2br($model_info->location); ?></div>
                </div>
            <?php }
            ?>

            <div class="col-md-12 pt10 pb10">
                <?php
                $image_url = get_avatar($model_info->created_by_avatar);
                echo "<span class='avatar avatar-xs mr10'><img src='$image_url' alt=''></span><span>" . get_team_member_profile_link($model_info->created_by, $model_info->created_by_name, array("class" => "dark strong")) . "</span>";
                ?>
            </div>


            <?php if ($confirmed_by) { ?>
                <div class="col-md-12 clearfix">
                    <div class="pl10 pr10">
                        <div class="row">
                            <div class="col-md-1 p0">
                                <span title="<?php echo app_lang("confirmed"); ?>" class='confirmed-by-logo'><span data-feather="check-circle"></span></span>
                            </div>
                            <div class="col-md-11 pt10 pl0">
                                <?php echo $confirmed_by; ?>
                            </div>
                        </div> 
                    </div>
                </div>
            <?php } ?>

            <?php if ($rejected_by) { ?>
                <div class="col-md-12 clearfix">
                    <div class="col-md-1 p0">
                        <span title="<?php echo app_lang("rejected"); ?>" class="rejected-by-logo"><i data-feather="x" class="icon-16"></i></span>
                    </div>
                    <div class="col-md-11 pt10 pl0">
                        <?php echo $rejected_by; ?>
                    </div>
                </div>
            <?php } ?>


            <?php
            if (count($custom_fields_list)) {
                foreach ($custom_fields_list as $data) {
                    if ($data->value) {
                        ?>
                        <div class="col-md-12 pt10">
                            <strong><?php echo $data->title . ": "; ?> </strong> <?php echo view("custom_fields/output_" . $data->field_type, array("value" => $data->value)); ?>
                        </div>
                        <?php
                    }
                }
            }
            ?>


        </div>
    </div>
</div>

<div class="modal-footer">
    <?php
    if (isset($editable) && $editable === "1") {

        if ($login_user->id == $model_info->created_by || $login_user->is_admin) {
            //recurring child event's can't be deleted
            $show_delete = true;

            if (isset($model_info->cycle) && $model_info->cycle) {
                $show_delete = false;
            }

            if ($show_delete) {
                echo js_anchor("<i data-feather='x-circle' class='icon-16'></i> " . app_lang('delete_event'), array("class" => "btn btn-default float-start", "id" => "delete_event", "data-encrypted_event_id" => $encrypted_event_id));
            }

            echo modal_anchor(get_uri("events/modal_form/"), "<i data-feather='edit' class='icon-16'></i> " . app_lang('edit_event'), array("class" => "btn btn-default", "data-post-encrypted_event_id" => $encrypted_event_id, "title" => app_lang('edit_event')));
        }
    }

    //show a button to confirm or reject the event
    if ($login_user->id != $model_info->created_by) {
        echo $status_button;
    }
    ?>
    <button type="button" class="btn btn-info text-white close-modal" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
</div>


<script type="text/javascript">
    $(document).ready(function () {

        $('#delete_event').click(function () {
            var encrypted_event_id = $(this).attr("data-encrypted_event_id");
            $(this).appConfirmation({
                title: "<?php echo app_lang('are_you_sure'); ?>",
                btnConfirmLabel: "<?php echo app_lang('yes'); ?>",
                btnCancelLabel: "<?php echo app_lang('no'); ?>",
                onConfirm: function () {
                    appLoader.show();
                    $('.close-modal').trigger("click");

                    $.ajax({
                        url: "<?php echo get_uri('events/delete') ?>",
                        type: 'POST',
                        dataType: 'json',
                        data: {encrypted_event_id: encrypted_event_id},
                        success: function (result) {
                            if (result.success) {
                                window.fullCalendar.refetchEvents();
                                setTimeout(function () {
                                    feather.replace();
                                }, 100);
                                appAlert.warning(result.message, {duration: 10000});
                            } else {
                                appAlert.error(result.message);
                            }

                            appLoader.hide();
                        }
                    });

                }
            });

            return false;
        });

        $('[data-bs-toggle="tooltip"]').tooltip();

    });
</script>    