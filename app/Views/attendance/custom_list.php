<div class="table-responsive">
    <table id="custom-attendance-table" class="display" cellspacing="0" width="100%">
    </table>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#custom-attendance-table").appTable({
            source: '<?php echo_uri("attendance/list_data/"); ?>',
            order: [[2, "desc"]],
            filterDropdown: [{name: "user_id", class: "w200", options: <?php echo $team_members_dropdown; ?>}],
            rangeDatepicker: [{startDate: {name: "start_date", value: moment().format("YYYY-MM-DD")}, endDate: {name: "end_date", value: moment().format("YYYY-MM-DD")}}],
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
    });
</script>