<div id="page-content" class="page-wrapper clearfix">

    <?php echo form_open(get_uri("todo/save"), array("id" => "todo-inline-form", "class" => "", "role" => "form")); ?>
    <div class="todo-input-box">

        <div class="input-group">
            <?php
            echo form_input(array(
                "id" => "todo-title",
                "name" => "title",
                "value" => "",
                "class" => "form-control",
                "placeholder" => app_lang('add_a_todo'),
                "autocomplete" => "off",
                "autofocus" => true
            ));
            ?>
            <span class="input-group-btn">
                <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
            </span>
        </div>

    </div>
    <?php echo form_close(); ?>


    <div class="card">
        <div class="page-title clearfix">
            <h1> <?php echo app_lang('todo') . " (" . app_lang('private') . ")"; ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("labels/modal_form"), "<i data-feather='tag' class='icon-16'></i> " . app_lang('manage_labels'), array("class" => "btn btn-default", "title" => app_lang('manage_labels'), "data-post-type" => "to_do")); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="todo-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<?php echo view("todo/helper_js"); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#todo-table").appTable({
            source: '<?php echo_uri("todo/list_data") ?>',
            order: [[1, 'desc']],
            columns: [
                {visible: false, searchable: false},
                {title: '', "class": "w25"},
                {title: '<?php echo app_lang("title"); ?>'},
                {visible: false, searchable: false},
                {title: '<?php echo app_lang("date"); ?>', "iDataSort": 3, "class": "w200"},
                {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
            ],
            checkBoxes: [
                {text: '<?php echo app_lang("to_do") ?>', name: "status", value: "to_do", isChecked: true},
                {text: '<?php echo app_lang("done") ?>', name: "status", value: "done", isChecked: false}
            ],
            printColumns: [2, 4],
            xlsColumns: [2, 4],
            rowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $('td:eq(0)', nRow).addClass(aData[0]);
            }
        });
    });
</script>