<?php if ($page_view) { ?>
    <div id="page-content" class="page-wrapper clearfix">
        <div class="card clearfix">
        <?php } ?>

        <div class="card rounded-0">
            <div class="tab-title clearfix">
                <h4><?php echo app_lang('files'); ?></h4>
                <div class="title-button-group">
                    <?php
                    if ($login_user->user_type == "staff" || ($login_user->user_type == "client" && get_setting("client_can_add_files"))) {
                        echo modal_anchor(get_uri("clients/file_modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_files'), array("class" => "btn btn-default", "title" => app_lang('add_files'), "data-post-client_id" => $client_id));
                    }
                    ?>
                </div>
            </div>

            <div class="table-responsive">
                <table id="client-file-table" class="display" width="100%">            
                </table>
            </div>
        </div>

        <?php if ($page_view) { ?>
        </div>
    </div>
<?php } ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#client-file-table").appTable({
            source: '<?php echo_uri("clients/files_list_data/" . $client_id) ?>',
            order: [[0, "desc"]],
            columns: [
                {title: '<?php echo app_lang("id") ?>'},
                {title: '<?php echo app_lang("file") ?>'},
                {title: '<?php echo app_lang("size") ?>'},
                {title: '<?php echo app_lang("uploaded_by") ?>'},
                {title: '<?php echo app_lang("created_date") ?>'},
                {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4],
            xlsColumns: [0, 1, 2, 3, 4]
        });
    });
</script>