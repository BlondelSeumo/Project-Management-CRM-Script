<div id="page-content" class="page-wrapper clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "pages";
            echo view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <div class="card">
                <div class="page-title clearfix">
                    <h4> <?php echo app_lang('pages'); ?></h4>
                    <div class="title-button-group">
                        <?php echo modal_anchor(get_uri("pages/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_page'), array("class" => "btn btn-default", "title" => app_lang('add_page'))); ?>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="pages-table" class="display" cellspacing="0" width="100%">            
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#pages-table").appTable({
            source: '<?php echo_uri("pages/list_data") ?>',
            columns: [
                {title: '<?php echo app_lang("title"); ?>'},
                {title: '<?php echo app_lang("url"); ?>'},
                {title: '<?php echo app_lang("status"); ?>'},
                {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
            ]
        });
    });
</script>