<div class="card">
    <div class="tab-title clearfix">
        <h4><?php echo app_lang('notes') . " (" . app_lang('private') . ")"; ?></h4>
        <div class="title-button-group">
            <?php echo modal_anchor(get_uri("notes/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_note'), array("class" => "btn btn-default", "title" => app_lang('add_note'), "data-post-project_id" => $project_id)); ?>           
        </div>
    </div>
    <div class="table-responsive">
        <table id="note-table" class="display" cellspacing="0" width="100%">            
        </table>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        $("#note-table").appTable({
            source: '<?php echo_uri("notes/list_data/project/" . $project_id) ?>',
            order: [[0, 'desc']],
            columns: [
                {targets: [1], visible: false},
                {title: '<?php echo app_lang("created_date"); ?>', "class": "w200"},
                {title: '<?php echo app_lang("title"); ?>'},
                {title: '<?php echo app_lang("files"); ?>', "class": "w250"},
                {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
            ]
        });
    });
</script>