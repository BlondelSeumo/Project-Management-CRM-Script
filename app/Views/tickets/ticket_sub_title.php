<div class="bg-white p15 pt0 b-b">
    <?php
    $ticket_labels = make_labels_view_data($ticket_info->labels_list);
    echo "<span class='mr15'>" . $ticket_labels . " </span>"
    ?>

    <span class="text-off"><?php echo app_lang("status") . ": "; ?></span>

    <?php
    $ticket_status_class = "bg-danger";
    if ($ticket_info->status === "new") {
        $ticket_status_class = "bg-warning";
    } else if ($ticket_info->status === "closed") {
        $ticket_status_class = "bg-success";
    }

    if ($ticket_info->status === "client_replied" && $login_user->user_type === "client") {
        $ticket_info->status = "open"; //don't show client_replied status to client
    }

    $ticket_status = "<span class='badge $ticket_status_class large'>" . app_lang($ticket_info->status) . "</span> ";
    echo $ticket_status;
    ?>
    <?php if ($login_user->user_type === "staff" && $ticket_info->client_id) { ?>
        <span class="text-off ml15"><?php echo app_lang("client") . ": "; ?></span>
        <?php echo $ticket_info->company_name ? anchor(get_uri("clients/view/" . $ticket_info->client_id), $ticket_info->company_name) : "-"; ?>

        <?php if ($ticket_info->requested_by) { ?>
            <span class="text-off ml15"><?php echo app_lang("requested_by") . ": "; ?></span>
            <?php echo anchor(get_uri("clients/contact_profile/" . $ticket_info->requested_by), $ticket_info->requested_by_name); ?>
        <?php } ?>

    <?php } ?>

    <?php if ($ticket_info->project_id != "0" && $show_project_reference == "1") { ?>
        <span class="text-off ml15"><?php echo app_lang("project") . ": "; ?></span>
        <?php echo $ticket_info->project_title ? anchor(get_uri("projects/view/" . $ticket_info->project_id), $ticket_info->project_title) : "-"; ?>
    <?php } ?>

    <span class="text-off ml15"><?php echo app_lang("created") . ": "; ?></span>
    <?php echo format_to_relative_time($ticket_info->created_at); ?> 

    <?php if ($ticket_info->closed_at && $ticket_info->status == "closed") { ?>
        <span class="text-off ml15"><?php echo app_lang("closed") . ": "; ?></span>
        <?php echo format_to_relative_time($ticket_info->closed_at); ?> 
    <?php } ?>

    <?php if ($ticket_info->ticket_type) { ?>
        <span class="text-off ml15"><?php echo app_lang("ticket_type") . ": "; ?></span>
        <?php echo $ticket_info->ticket_type; ?> 
    <?php } ?>

    <?php
    if ($ticket_info->assigned_to && $login_user->user_type == "staff") {
        //show assign to field to team members only

        $image_url = get_avatar($ticket_info->assigned_to_avatar);
        $assigned_to_user = "<span class='avatar avatar-xs mr10'><img src='$image_url' alt='...'></span> $ticket_info->assigned_to_user";
        ?>
        <span class="text-off ml15 mr10"><?php echo app_lang("assigned_to") . ": "; ?></span>
        <?php
        echo get_team_member_profile_link($ticket_info->assigned_to, $assigned_to_user);
    }
    ?>

    <?php if ($ticket_info->task_id != "0") { ?>
        <span class="text-off ml15"><?php echo app_lang("task") . ": "; ?></span>
        <?php echo modal_anchor(get_uri("projects/task_view"), $ticket_info->task_title, array("title" => app_lang('task_info') . " #$ticket_info->task_id", "data-post-id" => $ticket_info->task_id, "data-modal-lg" => "1")) ?>
    <?php } ?>
</div>

<?php
if (count($custom_fields_list)) {
    $fields = "";
    foreach ($custom_fields_list as $data) {
        if ($data->value) {
            $fields .= "<div class='p15 bg-white b-b '><i data-feather='check-square' class='icon-16 ml15'></i> <span class='text-off'> $data->title:</span> " . view("custom_fields/output_" . $data->field_type, array("value" => $data->value)) . "</div>";
        }
    }
    if ($fields) {
        echo $fields;
    }
}