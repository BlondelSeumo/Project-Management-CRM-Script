<div id="page-content" class="page-wrapper clearfix">
    <div class="card clearfix">
        <ul id="client-tabs" data-bs-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li><a id="clients-button" role="presentation" href="javascript:;" data-bs-target="#clients"><?php echo app_lang('clients'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("clients/contacts/"); ?>" data-bs-target="#contacts"><?php echo app_lang('contacts'); ?></a></li>
            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    <?php if ($can_edit_clients) { ?>
                        <?php echo modal_anchor(get_uri("clients/import_clients_modal_form"), "<i data-feather='upload' class='icon-16'></i> " . app_lang('import_clients'), array("class" => "btn btn-default", "title" => app_lang('import_clients'))); ?>
                        <?php echo modal_anchor(get_uri("clients/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_client'), array("class" => "btn btn-default", "title" => app_lang('add_client'))); ?>
                    <?php } ?>
                </div>
            </div>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="clients">
                <div class="table-responsive">
                    <table id="client-table" class="display" cellspacing="0" width="100%">            
                    </table>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="contacts"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    loadClientsTable = function (selector) {
        var showInvoiceInfo = true;
        if (!"<?php echo $show_invoice_info; ?>") {
            showInvoiceInfo = false;
        }
        
        var showOptions = true;
        if (!"<?php echo $can_edit_clients; ?>") {
            showOptions = false;
        }

        $(selector).appTable({
            source: '<?php echo_uri("clients/list_data") ?>',
            filterDropdown: [
                {name: "group_id", class: "w200", options: <?php echo $groups_dropdown; ?>},
                {name: "quick_filter", class: "w200", options: <?php echo view("clients/quick_filters_dropdown"); ?>},
                <?php if($login_user->is_admin || get_array_value($login_user->permissions, "client") === "all"){ ?>
                    {name: "created_by", class: "w200", options: <?php echo $team_members_dropdown; ?>}
                <?php } ?>
            ],
            columns: [
                {title: "<?php echo app_lang("id") ?>", "class": "text-center w50"},
                {title: "<?php echo app_lang("company_name") ?>"},
                {title: "<?php echo app_lang("primary_contact") ?>"},
                {title: "<?php echo app_lang("client_groups") ?>"},
                {title: "<?php echo app_lang("projects") ?>"},
                {visible: showInvoiceInfo, searchable: showInvoiceInfo, title: "<?php echo app_lang("invoice_value") ?>"},
                {visible: showInvoiceInfo, searchable: showInvoiceInfo, title: "<?php echo app_lang("payment_received") ?>"},
                {visible: showInvoiceInfo, searchable: showInvoiceInfo, title: "<?php echo app_lang("due") ?>"}
<?php echo $custom_field_headers; ?>,
                {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100", visible: showOptions}
            ],
            printColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4, 5, 6], '<?php echo $custom_field_headers; ?>'),
            xlsColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4, 5, 6], '<?php echo $custom_field_headers; ?>')
        });
    };

    $(document).ready(function () {
        loadClientsTable("#client-table");

        setTimeout(function () {
            var tab = "<?php echo $tab; ?>";
            if (tab === "contacts") {
                $("[data-bs-target='#contacts']").trigger("click");
            }
        }, 210);
    });
</script>