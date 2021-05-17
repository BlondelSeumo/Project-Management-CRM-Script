<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h1><?php echo app_lang('projects'); ?></h1>
            <div class="title-button-group">
                <?php
                if ($can_create_projects) {
                    if ($can_edit_projects) {
                        echo modal_anchor(get_uri("labels/modal_form"), "<i data-feather='tag' class='icon-16'></i> " . app_lang('manage_labels'), array("class" => "btn btn-default", "title" => app_lang('manage_labels'), "data-post-type" => "project"));
                    }

                    echo modal_anchor(get_uri("projects/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_project'), array("class" => "btn btn-default", "title" => app_lang('add_project')));
                }
                ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="project-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var optionVisibility = false;
        if ("<?php echo ($can_edit_projects || $can_delete_projects); ?>") {
            optionVisibility = true;
        }

        var selectOpenStatus = true, selectCompletedStatus = false;
<?php if (isset($status) && $status == "completed") { ?>
            selectOpenStatus = false;
            selectCompletedStatus = true;
<?php } ?>

        $("#project-table").appTable({
            source: '<?php echo_uri("projects/list_data") ?>',
            multiSelect: [
                {
                    name: "status",
                    text: "<?php echo app_lang('status'); ?>",
                    options: [
                        {text: '<?php echo app_lang("open") ?>', value: "open", isChecked: selectOpenStatus},
                        {text: '<?php echo app_lang("completed") ?>', value: "completed", isChecked: selectCompletedStatus},
                        {text: '<?php echo app_lang("hold") ?>', value: "hold"},
                        {text: '<?php echo app_lang("canceled") ?>', value: "canceled"}
                    ]
                }
            ],
            filterDropdown: [{name: "project_label", class: "w200", options: <?php echo $project_labels_dropdown; ?>}],
            singleDatepicker: [{name: "deadline", defaultText: "<?php echo app_lang('deadline') ?>",
                    options: [
                        {value: "expired", text: "<?php echo app_lang('expired') ?>"},
                        {value: moment().format("YYYY-MM-DD"), text: "<?php echo app_lang('today') ?>"},
                        {value: moment().add(1, 'days').format("YYYY-MM-DD"), text: "<?php echo app_lang('tomorrow') ?>"},
                        {value: moment().add(7, 'days').format("YYYY-MM-DD"), text: "<?php echo sprintf(app_lang('in_number_of_days'), 7); ?>"},
                        {value: moment().add(15, 'days').format("YYYY-MM-DD"), text: "<?php echo sprintf(app_lang('in_number_of_days'), 15); ?>"}
                    ]}],
            columns: [
                {title: '<?php echo app_lang("id") ?>', "class": "w50"},
                {title: '<?php echo app_lang("title") ?>'},
                {title: '<?php echo app_lang("client") ?>', "class": "w10p"},
                {visible: optionVisibility, title: '<?php echo app_lang("price") ?>', "class": "w10p"},
                {visible: false, searchable: false},
                {title: '<?php echo app_lang("start_date") ?>', "class": "w10p", "iDataSort": 4},
                {visible: false, searchable: false},
                {title: '<?php echo app_lang("deadline") ?>', "class": "w10p", "iDataSort": 6},
                {title: '<?php echo app_lang("progress") ?>', "class": "w10p"},
                {title: '<?php echo app_lang("status") ?>', "class": "w10p"}
<?php echo $custom_field_headers; ?>,
                {visible: optionVisibility, title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
            ],
            order: [[1, "desc"]],
            printColumns: combineCustomFieldsColumns([0, 1, 2, 3, 5, 7, 8, 9], '<?php echo $custom_field_headers; ?>'),
            xlsColumns: combineCustomFieldsColumns([0, 1, 2, 3, 5, 7, 8, 9], '<?php echo $custom_field_headers; ?>')
        });
    });
</script>