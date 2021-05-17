<div class="card">
    <div class="tab-title clearfix">
        <h4><?php echo app_lang('milestones'); ?></h4>
        <div class="title-button-group">
            <?php
            if ($can_create_milestones) {
                echo modal_anchor(get_uri("projects/milestone_modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_milestone'), array("class" => "btn btn-default", "title" => app_lang('add_milestone'), "data-post-project_id" => $project_id));
            }
            ?> 
        </div>
    </div>

    <div class="table-responsive">
        <table id="milestone-table" class="display" width="100%">            
        </table>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        var optionVisibility = false;
        if ("<?php echo ($can_edit_milestones || $can_delete_milestones); ?>") {
            optionVisibility = true;
        }
        $("#milestone-table").appTable({
            source: '<?php echo_uri("projects/milestones_list_data/" . $project_id) ?>',
            order: [[0, "dasc"]],
            columns: [
                {visible: false, searchable: false},
                {title: "<?php echo app_lang("due_date") ?>", "class": "text-center w100", "iDataSort": 0},
                {title: "<?php echo app_lang("title") ?>"},
                {title: "<?php echo app_lang("progress") ?>", "class": "text-center w30p"},
                {visible: optionVisibility, title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
            ],
            printColumns: [1, 2, 3, 4]
        });
    });
</script>