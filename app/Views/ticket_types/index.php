<div id="page-content" class="page-wrapper clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "tickets";
            echo view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <div class="card">

                <ul id="ticket-type-tabs" data-bs-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
                    <li><a  role="presentation"  href="javascript:;" data-bs-target="#ticket-types-tab"> <?php echo app_lang('ticket_types'); ?></a></li>
                    <li><a role="presentation" href="<?php echo_uri("settings/tickets/"); ?>" data-bs-target="#tickets-tab"><?php echo app_lang('tickets'); ?></a></li>
                    <li><a role="presentation" href="<?php echo_uri("settings/imap_settings/"); ?>" data-bs-target="#imap_settings-tab"><?php echo app_lang('imap_settings'); ?></a></li>
                    <div class="tab-title clearfix no-border">
                        <div class="title-button-group">
                            <?php echo modal_anchor(get_uri("ticket_types/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_ticket_type'), array("class" => "btn btn-default", "title" => app_lang('add_ticket_type'))); ?>
                        </div>
                    </div>
                </ul>

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade" id="ticket-types-tab">
                        <div class="table-responsive">
                            <table id="ticket-type-table" class="display" cellspacing="0" width="100%">            
                            </table>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="tickets-tab"></div>
                    <div role="tabpanel" class="tab-pane fade" id="imap_settings-tab"></div>
                </div>

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#ticket-type-table").appTable({
            source: '<?php echo_uri("ticket_types/list_data") ?>',
            columns: [
                {title: '<?php echo app_lang("name"); ?>'},
                {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 1]
        });

        setTimeout(function () {
            var tab = "<?php echo $tab; ?>";
            if (tab === "imap") {
                $("[data-bs-target='#imap_settings-tab']").trigger("click");
            }
        }, 210);

    });
</script>
