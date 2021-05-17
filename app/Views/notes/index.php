<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h1> <?php echo app_lang('notes') . " (" . app_lang('private') . ")"; ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("labels/modal_form"), "<i data-feather='tag' class='icon-16'></i> " . app_lang('manage_labels'), array("class" => "btn btn-default", "title" => app_lang('manage_labels'), "data-post-type" => "note")); ?>
                <?php echo modal_anchor(get_uri("notes/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_note'), array("class" => "btn btn-default", "title" => app_lang('add_note'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="note-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#note-table").appTable({
            source: '<?php echo_uri("notes/list_data") ?>',
            order: [[0, 'desc']],
            columns: [
                {targets: [1], visible: false},
                {title: '<?php echo app_lang("created_date"); ?>', "class": "w200"},
                {title: '<?php echo app_lang("title"); ?>'},
                {title: '<?php echo app_lang("files") ?>', "class": "w250"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w100"}
            ]
        });
    });
</script>