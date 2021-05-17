<div class="card rounded-0">
    <div class="tab-title clearfix">
        <h4><?php echo app_lang('files'); ?></h4>
        <div class="title-button-group">
            <?php
            echo modal_anchor(get_uri("team_members/file_modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_files'), array("class" => "btn btn-default", "title" => app_lang('add_files'), "data-post-user_id" => $user_id));
            ?>
        </div>
    </div>

    <div class="table-responsive">
        <table id="team-member-file-table" class="display" width="100%">            
        </table>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {


        $("#team-member-file-table").appTable({
            source: '<?php echo_uri("team_members/files_list_data/" . $user_id) ?>',
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