<div class="card bg-white">
    <div class="card-header no-border">
        <i data-feather="list" class="icon-16"></i>&nbsp; <?php echo app_lang('my_tasks'); ?>
    </div>

    <div class="table-responsive" id="my-task-list-widget-table">
        <table id="task-table" class="display" cellspacing="0" width="100%">            
        </table>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        initScrollbar('#my-task-list-widget-table', {
            setHeight: 330
        });

        var showOption = true,
                idColumnClass = "w70",
                titleColumnClass = "";

        if (isMobile()) {
            showOption = false;
            idColumnClass = "w25p";
            titleColumnClass = "w75p";
        }

        $("#task-table").appTable({
            source: '<?php echo_uri("projects/my_tasks_list_data/1") ?>',
            order: [[5, "desc"]],
            displayLength: 30,
            responsive: false, //hide responsive (+) icon
            columns: [
                {visible: false, searchable: false},
                {title: '<?php echo app_lang("id") ?>', "class": idColumnClass},
                {title: '<?php echo app_lang("title") ?>', "class": titleColumnClass},
                {visible: false, searchable: false},
                {title: '<?php echo app_lang("start_date") ?>', "iDataSort": 3, "class": "w70", visible: showOption},
                {visible: false, searchable: false},
                {title: '<?php echo app_lang("deadline") ?>', "iDataSort": 5, "class": "w70", visible: showOption},
                {visible: false, searchable: false},
                {visible: false, searchable: false},
                {visible: false, searchable: false},
                {title: '<?php echo app_lang("status") ?>', "class": "w70", visible: showOption},
                {visible: false, searchable: false}
            ],
            onInitComplete: function () {
                $("#task-table_wrapper .datatable-tools").addClass("hide");
            },
            rowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $('td:eq(0)', nRow).attr("style", "border-left:5px solid " + aData[0] + " !important;");
            }
        });
    });
</script>
<?php echo view("projects/tasks/update_task_script"); ?>