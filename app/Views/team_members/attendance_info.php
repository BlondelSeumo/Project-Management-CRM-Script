<div class="card clearfix rounded-0 <?php
if (isset($page_type) && $page_type === "full") {
    echo "m20";
}
?>">
    <ul id="team-member-attendance-tabs" data-bs-toggle="ajax-tab" class="nav nav-tabs bg-white rounded-0 title" role="tablist">
        <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php
                if ($user_id === $login_user->id) {
                    echo app_lang("my_time_cards");
                } else {
                    echo app_lang("attendance");
                }
                ?></h4></li>
        <li><a id="monthly-attendance-button"  role="presentation"  href="javascript:;" data-bs-target="#team_member-monthly-attendance"><?php echo app_lang("monthly"); ?></a></li>
        <li><a role="presentation" href="<?php echo_uri("team_members/weekly_attendance/"); ?>" data-bs-target="#team_member-weekly-attendance"><?php echo app_lang('weekly'); ?></a></li>    
        <li><a role="presentation" href="<?php echo_uri("team_members/custom_range_attendance/"); ?>" data-bs-target="#team_member-custom-range-attendance"><?php echo app_lang('custom'); ?></a></li>    
        <li><a role="presentation" href="<?php echo_uri("team_members/attendance_summary/" . $user_id); ?>" data-bs-target="#team_member-attendance-summary"><?php echo app_lang('summary'); ?></a></li>   

        <?php if (isset($show_clock_in_out)) { ?>
            <li><a role="presentation" href="<?php echo_uri("attendance/clock_in_out"); ?>" data-bs-target="#clock-in-out"><?php echo app_lang('clock_in_out'); ?></a></li>
        <?php } ?>

    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade" id="team_member-monthly-attendance">
            <div class="table-responsive">
                <table id="monthly-attendance-table" class="display" cellspacing="0" width="100%">    
                </table>
            </div>
            <script type="text/javascript">
                loadMembersAttendanceTable = function (selector, type) {
                    var rangeDatepicker = [],
                            dateRangeType = "";

                    if (type === "custom_range") {
                        rangeDatepicker = [{startDate: {name: "start_date", value: moment().format("YYYY-MM-DD")}, endDate: {name: "end_date", value: moment().format("YYYY-MM-DD")}}];
                    } else {
                        dateRangeType = type;
                    }

                    $(selector).appTable({
                        source: '<?php echo_uri("attendance/list_data/"); ?>',
                        order: [[2, "desc"]],
                        dateRangeType: dateRangeType,
                        rangeDatepicker: rangeDatepicker,
                        filterParams: {user_id: "<?php echo $user_id; ?>"},
                        columns: [
                            {targets: [1], visible: false, searchable: false},
                            {visible: false, searchable: false},
                            {title: "<?php echo app_lang("in_date"); ?>", "class": "w20p", iDataSort: 1},
                            {title: "<?php echo app_lang("in_time"); ?>", "class": "w20p"},
                            {visible: false, searchable: false},
                            {title: "<?php echo app_lang("out_date"); ?>", "class": "w20p", iDataSort: 1},
                            {title: "<?php echo app_lang("out_time"); ?>", "class": "w20p"},
                            {title: "<?php echo app_lang("duration"); ?>", "class": "text-right"},
                            {title: '<i data-feather="message-circle" class="icon-16"></i>', "class": "text-center w50"},
                            {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
                        ],
                        printColumns: [2, 3, 5, 6, 7],
                        xlsColumns: [2, 3, 5, 6, 7],
                        summation: [{column: 7, dataType: 'time'}]
                    });
                };
                $(document).ready(function () {
                    loadMembersAttendanceTable("#monthly-attendance-table", "monthly");
                });
            </script>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="team_member-weekly-attendance"></div>
        <div role="tabpanel" class="tab-pane fade" id="team_member-custom-range-attendance"></div>
        <div role="tabpanel" class="tab-pane fade" id="team_member-attendance-summary"></div>
        <div role="tabpanel" class="tab-pane fade" id="clock-in-out"></div>
    </div>
</div>