<div class="page-title clearfix no-border no-border-top-radius no-bg">
    <h1>
        <?php echo app_lang('client_details') . " - " . $client_info->company_name ?>
        <span id="star-mark">
            <?php
            if ($is_starred) {
                echo view('clients/star/starred', array("client_id" => $client_info->id));
            } else {
                echo view('clients/star/not_starred', array("client_id" => $client_info->id));
            }
            ?>
        </span>
    </h1>
</div>

<div id="page-content" class="clearfix">

    <?php
    if ($client_info->lead_status_id) {
        echo view("clients/lead_information");
    }
    ?>

    <div class="client-widget-section">
        <?php echo view("clients/info_widgets/index"); ?>
    </div>

    <ul id="client-tabs" data-bs-toggle="ajax-tab" class="nav nav-tabs scrollable-tabs no-border-top-radius" role="tablist">
        <li><a  role="presentation" href="<?php echo_uri("clients/contacts/" . $client_info->id); ?>" data-bs-target="#client-contacts"> <?php echo app_lang('contacts'); ?></a></li>
        <li><a  role="presentation" href="<?php echo_uri("clients/company_info_tab/" . $client_info->id); ?>" data-bs-target="#client-info"> <?php echo app_lang('client_info'); ?></a></li>
        <li><a  role="presentation" href="<?php echo_uri("clients/projects/" . $client_info->id); ?>" data-bs-target="#client-projects"><?php echo app_lang('projects'); ?></a></li>

        <?php if ($show_invoice_info) { ?>
            <li><a  role="presentation" href="<?php echo_uri("clients/invoices/" . $client_info->id); ?>" data-bs-target="#client-invoices"> <?php echo app_lang('invoices'); ?></a></li>
            <li><a  role="presentation" href="<?php echo_uri("clients/payments/" . $client_info->id); ?>" data-bs-target="#client-payments"> <?php echo app_lang('payments'); ?></a></li>
        <?php } ?>
        <?php if ($show_estimate_info) { ?>
            <li><a  role="presentation" href="<?php echo_uri("clients/estimates/" . $client_info->id); ?>" data-bs-target="#client-estimates"> <?php echo app_lang('estimates'); ?></a></li>
        <?php } ?>
        <?php if ($show_order_info) { ?>
            <li><a  role="presentation" href="<?php echo_uri("clients/orders/" . $client_info->id); ?>" data-bs-target="#client-orders"> <?php echo app_lang('orders'); ?></a></li>
        <?php } ?>
        <?php if ($show_estimate_request_info) { ?>
            <li><a  role="presentation" href="<?php echo_uri("clients/estimate_requests/" . $client_info->id); ?>" data-bs-target="#client-estimate-requests"> <?php echo app_lang('estimate_requests'); ?></a></li>
        <?php } ?>
        <?php if ($show_ticket_info) { ?>
            <li><a  role="presentation" href="<?php echo_uri("clients/tickets/" . $client_info->id); ?>" data-bs-target="#client-tickets"> <?php echo app_lang('tickets'); ?></a></li>
        <?php } ?>
        <?php if ($show_note_info) { ?>
            <li><a  role="presentation" href="<?php echo_uri("clients/notes/" . $client_info->id); ?>" data-bs-target="#client-notes"> <?php echo app_lang('notes'); ?></a></li>
        <?php } ?>
        <li><a  role="presentation" href="<?php echo_uri("clients/files/" . $client_info->id); ?>" data-bs-target="#client-files"><?php echo app_lang('files'); ?></a></li>

        <?php if ($show_event_info) { ?>
            <li><a  role="presentation" href="<?php echo_uri("clients/events/" . $client_info->id); ?>" data-bs-target="#client-events"> <?php echo app_lang('events'); ?></a></li>
        <?php } ?>

        <?php if ($show_expense_info) { ?>
            <li><a  role="presentation" href="<?php echo_uri("clients/expenses/" . $client_info->id); ?>" data-bs-target="#client-expenses"> <?php echo app_lang('expenses'); ?></a></li>
        <?php } ?>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade" id="client-projects"></div>
        <div role="tabpanel" class="tab-pane fade" id="client-files"></div>
        <div role="tabpanel" class="tab-pane fade" id="client-info"></div>
        <div role="tabpanel" class="tab-pane fade" id="client-contacts"></div>
        <div role="tabpanel" class="tab-pane fade" id="client-invoices"></div>
        <div role="tabpanel" class="tab-pane fade" id="client-payments"></div>
        <div role="tabpanel" class="tab-pane fade" id="client-estimates"></div>
        <div role="tabpanel" class="tab-pane fade" id="client-orders"></div>
        <div role="tabpanel" class="tab-pane fade" id="client-estimate-requests"></div>
        <div role="tabpanel" class="tab-pane fade" id="client-tickets"></div>
        <div role="tabpanel" class="tab-pane fade" id="client-notes"></div>
        <div role="tabpanel" class="tab-pane" id="client-events" style="min-height: 300px"></div>
        <div role="tabpanel" class="tab-pane fade" id="client-expenses"></div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        setTimeout(function () {
            var tab = "<?php echo $tab; ?>";
            if (tab === "info") {
                $("[data-bs-target='#client-info']").trigger("click");
            } else if (tab === "projects") {
                $("[data-bs-target='#client-projects']").trigger("click");
            } else if (tab === "invoices") {
                $("[data-bs-target='#client-invoices']").trigger("click");
            } else if (tab === "payments") {
                $("[data-bs-target='#client-payments']").trigger("click");
            }
        }, 210);

    });
</script>
