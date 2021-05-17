<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h1><?php echo app_lang('announcements'); ?></h1>
            <div class="title-button-group">
                <?php
                if ($show_add_button) {
                    echo anchor(get_uri("announcements/form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_announcement'), array("class" => "btn btn-default", "title" => app_lang('add_announcement')));
                };
                ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="announcement-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var showUserInfo = true;
        if ("<?php echo $login_user->user_type; ?>" === "client") {
            showUserInfo = false;
        }

        var showOption = true;
        if (("<?php echo $login_user->user_type; ?>" === "client") || ("<?php
                if (!$show_option) {
                    echo "0";
                }
                ?>" === "0")) {
            showOption = false;
        }

        $("#announcement-table").appTable({
            source: '<?php echo_uri("announcements/list_data") ?>',
            order: [[2, "desc"]],
            columns: [
                {title: '<?php echo app_lang("title") ?>'},
                {visible: showUserInfo, title: '<?php echo app_lang("created_by") ?>'},
                {visible: false, searchable: false},
                {title: '<?php echo app_lang("start_date") ?>', "iDataSort": 2},
                {visible: false, searchable: false},
                {title: '<?php echo app_lang("end_date") ?>', "iDataSort": 4},
                {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100", visible: showOption}
            ],
            printColumns: [0, 1, 3, 5]
        });
    });
</script>