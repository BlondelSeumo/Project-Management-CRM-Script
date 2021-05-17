<div id="page-content" class="page-wrapper clearfix">
    <ul class="nav nav-tabs bg-white title" role="tablist">
        <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo app_lang("leads"); ?></h4></li>

        <?php echo view("leads/tabs", array("active_tab" => "leads_list")); ?>

        <div class="tab-title clearfix no-border">
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("leads/import_leads_modal_form"), "<i data-feather='upload' class='icon-16'></i> " . app_lang('import_leads'), array("class" => "btn btn-default", "title" => app_lang('import_leads'))); ?>
                <?php echo modal_anchor(get_uri("leads/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_lead'), array("class" => "btn btn-default", "title" => app_lang('add_lead'))); ?>
            </div>
        </div>
    </ul>

    <div class="card">
        <div class="table-responsive">
            <table id="lead-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

    $("#lead-table").appTable({
    source: '<?php echo_uri("leads/list_data") ?>',
            columns: [
            {title: "<?php echo app_lang("company_name") ?>"},
            {title: "<?php echo app_lang("primary_contact") ?>"},
            {title: "<?php echo app_lang("owner") ?>"},
            {title: "<?php echo app_lang("status") ?>"}
<?php echo $custom_field_headers; ?>,
            {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
            ],
            filterDropdown: [
            {name: "status", class: "w200", options: <?php echo view("leads/lead_statuses"); ?>},
            {name: "source", class: "w200", options: <?php echo view("leads/lead_sources"); ?>},
<?php if (get_array_value($login_user->permissions, "lead") !== "own") { ?>
                {name: "owner_id", class: "w200", options: <?php echo json_encode($owners_dropdown); ?>}
<?php } ?>
            ],
            printColumns: combineCustomFieldsColumns([0, 1, 2], '<?php echo $custom_field_headers; ?>'),
            xlsColumns: combineCustomFieldsColumns([0, 1, 2], '<?php echo $custom_field_headers; ?>')
    });
    }
    );
</script>

<?php echo view("leads/update_lead_status_script"); ?>