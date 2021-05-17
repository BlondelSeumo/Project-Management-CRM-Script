<div id="page-content" class="page-wrapper clearfix">

    <div class="card">
        <ul id="attendance-tabs" data-bs-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo app_lang("attendance"); ?></h4></li>

            <li><a role="presentation"  href="javascript:;" data-bs-target="#daily-attendance"><?php echo app_lang("daily"); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("attendance/custom/"); ?>" data-bs-target="#custom-attendance"><?php echo app_lang('custom'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("attendance/summary/"); ?>" data-bs-target="#summary-attendance"><?php echo app_lang('summary'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("attendance/summary_details/"); ?>" data-bs-target="#summary-attendance-details"><?php echo app_lang('summary_details'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("attendance/members_clocked_in/"); ?>" data-bs-target="#members-clocked-in"><?php echo app_lang('members_clocked_in'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("attendance/clock_in_out"); ?>" data-bs-target="#clock-in-out"><?php echo app_lang('clock_in_out'); ?></a></li>

            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    <?php echo modal_anchor(get_uri("attendance/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_attendance'), array("class" => "btn btn-default", "title" => app_lang('add_attendance'))); ?>
                </div>
            </div>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="daily-attendance">
                <div class="table-responsive">
                    <table id="attendance-table" class="display" cellspacing="0" width="100%">            
                    </table>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="custom-attendance"></div>
            <div role="tabpanel" class="tab-pane fade" id="summary-attendance"></div>
            <div role="tabpanel" class="tab-pane fade" id="summary-attendance-details"></div>
            <div role="tabpanel" class="tab-pane fade" id="members-clocked-in"></div>
            <div role="tabpanel" class="tab-pane fade" id="clock-in-out"></div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        $("#attendance-table").appTable({
            source: '<?php echo_uri("attendance/list_data/"); ?>',
            order: [[2, "desc"]],
            filterDropdown: [{name: "user_id", class: "w200", options: <?php echo $team_members_dropdown; ?>}],
            dateRangeType: "daily",
            columns: [
                {title: "<?php echo app_lang("team_member"); ?>", "class": "w20p"},
                {visible: false, searchable: false},
                {title: "<?php echo app_lang("in_date"); ?>", "class": "w15p", iDataSort: 1},
                {title: "<?php echo app_lang("in_time"); ?>", "class": "w15p"},
                {visible: false, searchable: false},
                {title: "<?php echo app_lang("out_date"); ?>", "class": "w15p", iDataSort: 4},
                {title: "<?php echo app_lang("out_time"); ?>", "class": "w15p"},
                {title: "<?php echo app_lang("duration"); ?>", "class": "text-right"},
                {title: '<i data-feather="message-circle" class="icon-16"></i>', "class": "text-center w50"},
                {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 2, 3, 5, 6, 7],
            xlsColumns: [0, 2, 3, 5, 6, 7],
            summation: [{column: 7, dataType: 'time'}]
        });

        setTimeout(function () {
            var tab = "<?php echo $tab; ?>";
            if (tab === "members_clocked_in") {
                $("[data-bs-target='#members-clocked-in']").trigger("click");
            }
        }, 210);

    });
</script>    
