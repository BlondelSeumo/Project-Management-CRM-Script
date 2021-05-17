<?php echo form_open(get_uri("leaves/" . $form_type), array("id" => "leave-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <?php if ($form_type == "assign_leave") { ?>
            <div class="form-group">
                <div class="row">
                    <label for="applicant_id" class=" col-md-3"><?php echo app_lang('team_member'); ?></label>
                    <div class=" col-md-9">
                        <?php
                        if (isset($team_members_info)) {
                            $image_url = get_avatar($team_members_info->image);
                            echo "<span class='avatar avatar-xs mr10'><img src='$image_url' alt=''></span>" . $team_members_info->first_name . " " . $team_members_info->last_name;
                            ?>
                            <input type="hidden" name="applicant_id" value="<?php echo $team_members_info->id; ?>" />
                            <?php
                        } else {
                            echo form_dropdown("applicant_id", $team_members_dropdown, "", "class='select2 validate-hidden' id='applicant_id' data-rule-required='true', data-msg-required='" . app_lang('field_required') . "'");
                        }
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="form-group">
            <div class="row">
                <label for="leave_type" class=" col-md-3"><?php echo app_lang('leave_type'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_dropdown("leave_type_id", $leave_types_dropdown, "", "class='select2 validate-hidden' id='leave_type_id' data-rule-required='true', data-msg-required='" . app_lang('field_required') . "'");
                    ?>
                </div>
            </div>
        </div>
        <div class=" form-group">
            <div class="row">
                <label for="duration" class=" col-md-3"><?php echo app_lang('duration'); ?></label>
                <div class="col-md-9">

                    <?php
                    echo form_radio(array(
                        "id" => "duration_single_day",
                        "class" => "duration form-check-input",
                        "name" => "duration",
                            ), "single_day", true);
                    ?>
                    <label for="duration_single_day" class="mr15" ><?php echo app_lang('single_day'); ?></label>

                    <?php
                    echo form_radio(array(
                        "id" => "duration_mulitple_days",
                        "class" => "duration form-check-input",
                        "name" => "duration",
                            ), "multiple_days", false);
                    ?>
                    <label for="duration_mulitple_days" class="mr15" ><?php echo app_lang('mulitple_days'); ?></label>

                    <?php
                    echo form_radio(array(
                        "id" => "duration_hours",
                        "class" => "duration form-check-input",
                        "name" => "duration",
                            ), "hours", false);
                    ?>
                    <label for="duration_hours" ><?php echo app_lang('hours'); ?></label>
                </div>
            </div>
        </div>

        <div id="single_day_section"  class="form-group date_section">
            <div class="row">
                <label id="date_label" for="single_date" class=" col-md-3"><?php echo app_lang('date'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "single_date",
                        "name" => "single_date",
                        "class" => "form-control",
                        "placeholder" => app_lang('date'),
                        "autocomplete" => "off",
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div id="multiple_days_section" class="hide date_section">
            <div class="form-group">
                <div class="row">
                    <label for="start_date" class=" col-md-3"><?php echo app_lang('start_date'); ?></label>
                    <div class=" col-md-9">
                        <?php
                        echo form_input(array(
                            "id" => "start_date",
                            "name" => "start_date",
                            "class" => "form-control",
                            "placeholder" => app_lang('start_date'),
                            "autocomplete" => "off",
                            "data-rule-required" => true,
                            "data-msg-required" => app_lang("field_required")
                        ));
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <label for="end_date" class=" col-md-3"><?php echo app_lang('end_date'); ?></label>
                    <div class=" col-md-9">
                        <?php
                        echo form_input(array(
                            "id" => "end_date",
                            "name" => "end_date",
                            "class" => "form-control",
                            "placeholder" => app_lang('end_date'),
                            "autocomplete" => "off",
                            "data-rule-required" => true,
                            "data-msg-required" => app_lang("field_required"),
                            "data-rule-greaterThanOrEqual" => "#start_date",
                            "data-msg-greaterThanOrEqual" => app_lang("end_date_must_be_equal_or_greater_than_start_date"),
                            "data-rule-mustBeSameYear" => "#start_date"
                        ));
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div id="total_days_section" class="hide date_section">
            <div class="form-group">
                <div class="row">
                    <label for="total_days" class="col-md-3"><?php echo app_lang('total_days'); ?></label>
                    <div class="col-md-9 total-days">

                    </div>
                </div>
            </div>
        </div>

        <div id="hours_section" class="hide date_section">
            <div class="clearfix">
                <div class="row">
                    <label for="hour_date" class=" col-md-3"><?php echo app_lang('date'); ?></label>
                    <div class="col-md-4 form-group">
                        <?php
                        echo form_input(array(
                            "id" => "hour_date",
                            "name" => "hour_date",
                            "class" => "form-control",
                            "placeholder" => app_lang('date'),
                            "autocomplete" => "off",
                            "data-rule-required" => true,
                            "data-msg-required" => app_lang("field_required"),
                        ));
                        ?>
                    </div>

                    <label for="hours" class=" col-md-2"><?php echo app_lang('hours'); ?></label>
                    <div class=" col-md-3">
                        <?php
                        echo form_dropdown("hours", array(
                            "01" => "01",
                            "02" => "02",
                            "03" => "03",
                            "04" => "04",
                            "05" => "05",
                            "06" => "06",
                            "07" => "07",
                            "08" => "08",
                                ), "", "class='select2 validate-hidden' id='hours' data-rule-required='true', data-msg-required='" . app_lang('field_required') . "'");
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="reason" class=" col-md-3"><?php echo app_lang('reason'); ?></label>
                <div class=" col-md-9">
                    <?php
                    echo form_textarea(array(
                        "id" => "reason",
                        "name" => "reason",
                        "class" => "form-control",
                        "placeholder" => app_lang('reason'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang($form_type); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#leave-form").appForm({
            onSuccess: function (result) {
                location.reload();
            }
        });

        setDatePicker("#start_date, #end_date");

        setDatePicker("#single_date, #hour_date");


        $("#leave-form .select2").select2();

        $(".duration").click(function () {
            var value = $(this).val();
            $(".date_section").addClass("hide");
            if (value === "multiple_days") {
                $("#multiple_days_section").removeClass("hide");
            } else if (value === "hours") {
                $("#hours_section").removeClass("hide");
            } else {
                $("#single_day_section").removeClass("hide");
            }
        });


        $("#multiple_days_section").change(function () {
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            if (start_date && end_date) {
                $("#total_days_section").removeClass("hide");

                var start_date = moment($('#start_date').val(), getJsDateFormat().toUpperCase());
                var end_date = moment($('#end_date').val(), getJsDateFormat().toUpperCase());
                var total_days = end_date.diff(start_date, 'days');

                $('div.total-days').html((total_days * 1) + 1); //count the starting day too
            } else {
                $("#total_days_section").addClass("hide");
            }
        });

    });
</script>