<div class="bg-white  p15 no-border m0 rounded-bottom">
    <span class="mr10"><?php echo $invoice_status_label; ?></span>

    <?php echo make_labels_view_data($invoice_info->labels_list, "", true); ?>

    <?php if ($invoice_info->project_id) { ?>
        <span class="ml15"><?php echo app_lang("project") . ": " . anchor(get_uri("projects/view/" . $invoice_info->project_id), $invoice_info->project_title); ?></span>
    <?php } ?>

    <span class="ml15"><?php
        echo app_lang("client") . ": ";
        echo (anchor(get_uri("clients/view/" . $invoice_info->client_id), $invoice_info->company_name));
        ?>
    </span> 

    <span class="ml15"><?php
        echo app_lang("last_email_sent") . ": ";
        echo (is_date_exists($invoice_info->last_email_sent_date)) ? format_to_date($invoice_info->last_email_sent_date, FALSE) : app_lang("never");
        ?>
    </span>
    <?php if ($invoice_info->recurring_invoice_id) { ?>
        <span class="ml15">
            <?php
            echo app_lang("created_from") . ": ";
            echo anchor(get_uri("invoices/view/" . $invoice_info->recurring_invoice_id), get_invoice_id($invoice_info->recurring_invoice_id));
            ?>
        </span>
    <?php } ?>

    <?php if ($invoice_info->cancelled_at) { ?>
        <span class="ml15"><?php echo app_lang("cancelled_at") . ": " . format_to_relative_time($invoice_info->cancelled_at); ?></span>
    <?php } ?>

    <?php if ($invoice_info->cancelled_by) { ?>
        <span class="ml15"><?php echo app_lang("cancelled_by") . ": " . get_team_member_profile_link($invoice_info->cancelled_by, $invoice_info->cancelled_by_user); ?></span>
    <?php } ?>

</div>