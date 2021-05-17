<div class="bg-off-white p15 pt0">
    <span class="font-16"><?php echo app_lang("past_lead_information"); ?></span>

    <div class="mt5">
        <?php if ($client_info->created_date) { ?>
            <?php echo app_lang("lead_created_at") . ": " . format_to_date($client_info->created_date, false); ?>
        <?php } ?>
        <?php if ($client_info->client_migration_date && is_date_exists($client_info->client_migration_date)) { ?>
            <br /><?php echo app_lang("migrated_to_client_at") . ": " . format_to_date($client_info->client_migration_date, false); ?>
        <?php } ?>
        <?php if ($client_info->last_lead_status) { ?>
            <br /><?php echo app_lang("last_status") . ": " . $client_info->last_lead_status; ?>
        <?php } ?>
        <?php if ($client_info->owner_id) { ?>
            <br /><?php echo app_lang("owner") . ": " . get_team_member_profile_link($client_info->owner_id, $client_info->owner_name); ?>
        <?php } ?>
    </div>
</div>