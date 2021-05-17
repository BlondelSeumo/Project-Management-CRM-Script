<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h1><?php echo app_lang('users'); ?></h1>
            <div class="title-button-group">
                <?php
                if (!get_setting("disable_user_invitation_option_by_clients")) {
                    echo modal_anchor(get_uri("clients/invitation_modal"), "<i data-feather='mail' class='icon-16'></i> " . app_lang('send_invitation'), array("class" => "btn btn-default", "title" => app_lang('send_invitation'), "data-post-client_id" => $client_id));
                }
                ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="contact-table" class="display" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#contact-table").appTable({
            source: '<?php echo_uri("clients/contacts_list_data/" . $client_id) ?>',
            order: [[1, "asc"]],
            columns: [
                {title: '', "class": "w50 text-center"},
                {title: "<?php echo app_lang("name") ?>"},
                {visible: false, searchable: false},
                {title: "<?php echo app_lang("job_title") ?>", "class": "w15p"},
                {title: "<?php echo app_lang("email") ?>", "class": "w20p"},
                {title: "<?php echo app_lang("phone") ?>", "class": "w15p"},
                {title: 'Skype', "class": "w15p"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5],
            xlsColumns: [1, 2, 3, 4, 5]

        });
    });
</script>