<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h1> <?php echo app_lang('estimate_request_forms'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("estimate_requests/estimate_request_modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_form'), array("class" => "btn btn-default", "title" => app_lang('add_form'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="estimate-form-main-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#estimate-form-main-table").appTable({
            source: '<?php echo_uri("estimate_requests/estimate_forms_list_data") ?>',
            order: [[0, 'asc']],
            columns: [
                {title: "<?php echo app_lang("title"); ?>"},
                {title: "<?php echo app_lang("public"); ?>", "class": "w150"},
                {title: "<?php echo app_lang("embed"); ?>", "class": "option w150"},
                {title: "<?php echo app_lang("status"); ?>", "class": "w150"},
                {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
            ]
        });
    });
</script>