<div class="bg-white  p15 no-border m0 rounded-bottom">
    <span><?php echo app_lang("status") . ": " . $estimate_status_label; ?></span>
    <span class="ml15">
        <?php
        if ($estimate_info->is_lead) {
            echo app_lang("lead") . ": ";
            echo (anchor(get_uri("leads/view/" . $estimate_info->client_id), $estimate_info->company_name));
        } else {
            echo app_lang("client") . ": ";
            echo (anchor(get_uri("clients/view/" . $estimate_info->client_id), $estimate_info->company_name));
        }
        ?>
    </span>
    <span class="ml15"><?php
        echo app_lang("last_email_sent") . ": ";
        echo (is_date_exists($estimate_info->last_email_sent_date)) ? format_to_date($estimate_info->last_email_sent_date, FALSE) : app_lang("never");
        ?>
    </span>
    <?php if (!$estimate_info->estimate_request_id == 0) {
        ?>
        <span class="ml15">
            <?php
            echo app_lang("estimate_request") . ": ";
            echo (anchor(get_uri("estimate_requests/view_estimate_request/" . $estimate_info->estimate_request_id), app_lang('estimate_request') . " - " . $estimate_info->estimate_request_id));
            ?>
        </span>
        <?php
    }
    ?>
    <span class="ml15"><?php
        if ($estimate_info->project_id) {
            echo app_lang("project") . ": ";
            echo (anchor(get_uri("projects/view/" . $estimate_info->project_id), $estimate_info->project_title));
        }
        ?>
    </span>
</div>