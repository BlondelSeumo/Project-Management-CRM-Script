<div class="card">
    <div class="card-header">
        <h6 class="float-start"><?php echo app_lang('project_members'); ?></h6>
        <?php
        if ($can_add_remove_project_members) {
            echo modal_anchor(get_uri("projects/project_member_modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_member'), array("class" => "btn btn-outline-light float-end add-member-button", "title" => app_lang('add_member'), "data-post-project_id" => $project_id));
        }
        ?>
    </div>

    <div class="table-responsive">
        <table id="project-member-table" class="b-b-only no-thead" width="100%">            
        </table>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#project-member-table").appTable({
            source: '<?php echo_uri("projects/project_member_list_data/" . $project_id) ?>',
            hideTools: true,
            displayLength: 500,
            columns: [
                {title: ''},
                {title: '', "class": "text-center option w100"}
            ]
        });
    });
</script>