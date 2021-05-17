<div class="table-responsive">
    <table id="clock-in-out-table" class="display" cellspacing="0" width="100%">
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#clock-in-out-table").appTable({
            source: '<?php echo_uri("attendance/clock_in_out_list_data/"); ?>',
            order: [[0, "asc"]],
            columns: [
                {title: "<?php echo app_lang("team_members"); ?>"},
                {title: "<?php echo app_lang("status"); ?>", class: "w300"},
                {title: "<?php echo app_lang("clock_in_out"); ?>", class: "text-center w200"}
            ]
        });
    });
</script>