
<div id="page-content" class="clearfix">
    <div style="max-width: 1000px; margin: auto;">
        <div class="page-title clearfix mt25">


            <h1 ><?php echo app_lang("estimate_request"); ?> # <?php echo $model_info->id; ?></h1>

            <?php if ($show_actions) { ?>
                <div class="title-button-group p10">

                    <span class="dropdown inline-block">
                        <button class="btn btn-default dropdown-toggle caret mt0 mb0" type="button" data-bs-toggle="dropdown" aria-expanded="true">
                            <i data-feather='tool' class='icon-16'></i> <?php echo app_lang('actions'); ?>
                        </button>
                        <ul class="dropdown-menu float-end" role="menu">
                            <?php if ($login_user->user_type == "staff") { ?>
                                <li role="presentation">
                                    <?php echo modal_anchor(get_uri("estimate_requests/edit_estimate_request_modal_form"), "<i data-feather='edit' class='icon-16'></i> " . app_lang('edit'), array("title" => app_lang('estimate_request'), "data-post-view" => "details", "data-post-id" => $model_info->id, "class" => "dropdown-item")); ?>
                                </li>
                                <?php
                            }

                            echo view("estimate_requests/estimate_request_status_options");
                            ?>

                            <?php if ($login_user->user_type == "staff") { ?>
                                <li role="presentation">
                                    <?php echo modal_anchor(get_uri("estimates/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_estimate'), array("title" => app_lang('add_estimate'), "data-post-estimate_request_id" => $model_info->id, "data-post-client_id" => $model_info->client_id, "class" => "dropdown-item")); ?>    
                                </li>
                            <?php } ?>
                        </ul>
                    </span>
                </div>
            <?php } ?>
        </div>
        <div id="estimate-request-status-bar" class="card no-border">
            <div class="card-body">
                <span class="text-off"><?php echo app_lang("status") . ": "; ?></span>
                <?php echo $status; ?>

                <?php if ($show_client_info && $model_info->company_name) { ?>

                    <?php if ($model_info->is_lead) { ?>
                        <span class="text-off ml15"><?php echo app_lang("lead") . ": "; ?></span>
                        <?php echo (anchor(get_uri("leads/view/" . $model_info->client_id), $model_info->company_name)); ?>
                    <?php } else { ?>
                        <span class="text-off ml15"><?php echo app_lang("client") . ": "; ?></span>
                        <?php echo (anchor(get_uri("clients/view/" . $model_info->client_id), $model_info->company_name)); ?>
                    <?php } ?>

                <?php } ?>

                <span class="text-off ml15"><?php echo app_lang("created") . ": "; ?></span>
                <?php echo format_to_datetime($model_info->created_at); ?>

                <?php
                if ($show_assignee && $model_info->assigned_to) {
                    $image_url = get_avatar($model_info->assigned_to_avatar);
                    $assigned_to_user = "<span class='avatar avatar-xs mr10'><img src='$image_url' alt='...'></span> $model_info->assigned_to_user";
                    $assigned_to = get_team_member_profile_link($model_info->assigned_to, $assigned_to_user);
                    ?>
                    <span class="text-off ml15"><?php echo app_lang("assigned_to") . ":"; ?></span>
                    <span class="ml10"><?php echo $assigned_to; ?> </span>
                    <?php
                }
                ?>

                <?php
                if ($login_user->user_type == "staff" && $estimates) {
                    $estimate_lang = app_lang("estimate");
                    if (count($estimates) > 1) {
                        $estimate_lang = app_lang("estimates");
                    }
                    ?>

                    <span class="text-off ml15"><?php echo $estimate_lang . ": "; ?></span>

                    <?php
                    $last_estimate = end($estimates);
                    foreach ($estimates as $estimate) {
                        $seperation = ($estimate == $last_estimate) ? "" : ", ";
                        echo anchor(get_uri("estimates/view/" . $estimate->id), get_estimate_id($estimate->id)) . $seperation;
                    }
                    ?>
                <?php } ?>  
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h3 class="pl15 pr15">  <?php echo $model_info->form_title; ?></h3>

                <div class="table-responsive mt20 general-form">
                    <table id="estimate-request-table" class="display no-thead b-t b-b-only no-hover" cellspacing="0" width="100%">            
                    </table>
                </div>
                <div class="p15">
                    <?php
                    if ($model_info->files) {
                        $files = unserialize($model_info->files);
                        $total_files = count($files);
                        echo view("includes/timeline_preview", array("files" => $files));

                        if ($total_files && $show_download_option) {
                            $download_caption = app_lang('download');
                            if ($total_files > 1) {
                                $download_caption = sprintf(app_lang('download_files'), $total_files);
                            }

                            echo "<i data-feather='paperclip' class='icon-16 float-start'></i>";


                            echo anchor(get_uri("estimate_requests/download_estimate_request_files/" . $model_info->id), $download_caption, array("class" => "float-end", "title" => $download_caption));
                        }
                    }
                    ?>
                </div>

            </div>
        </div>


        <?php if ($lead_info) { ?>
            <div class="card">
                <div class="page-title ml15">
                    <h4><?php echo app_lang("lead_info"); ?></h4>
                </div>
                <div class="card-body">
                    <table class="display no-thead b-t b-b-only no-hover dataTable no-footer ">
                        <tbody>
                            <tr>
                                <td>
                                    <i data-feather="box" class="icon-16"></i><strong> <?php echo app_lang('company_name'); ?></strong>
                                    <div class="pl15"><?php echo $lead_info->company_name; ?></div>
                                </td>
                            </tr>

                            <?php if ($lead_info->address) { ?>
                                <tr>
                                    <td>
                                        <i data-feather="box" class="icon-16"></i><strong> <?php echo app_lang('address'); ?></strong>
                                        <div class="pl15"><?php echo nl2br($lead_info->address); ?></div>
                                    </td>
                                </tr>
                            <?php } ?>

                            <?php if ($lead_info->city) { ?>    
                                <tr>
                                    <td>
                                        <i data-feather="box" class="icon-16"></i><strong> <?php echo app_lang('city'); ?></strong>
                                        <div class="pl15"><?php echo $lead_info->city; ?></div>
                                    </td>
                                </tr>
                            <?php } ?>

                            <?php if ($lead_info->state) { ?>      
                                <tr>
                                    <td>
                                        <i data-feather="box" class="icon-16"></i><strong> <?php echo app_lang('state'); ?></strong>
                                        <div class="pl15"><?php echo $lead_info->state; ?></div>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if ($lead_info->zip) { ?>    
                                <tr>
                                    <td>
                                        <i data-feather="box" class="icon-16"></i><strong> <?php echo app_lang('zip'); ?></strong>
                                        <div class="pl15"><?php echo $lead_info->zip; ?></div>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if ($lead_info->country) { ?>    
                                <tr>
                                    <td>
                                        <i data-feather="box" class="icon-16"></i><strong> <?php echo app_lang('country'); ?></strong>
                                        <div class="pl15"><?php echo $lead_info->country; ?></div>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if ($lead_info->phone) { ?>    
                                <tr>
                                    <td>
                                        <i data-feather="box" class="icon-16"></i><strong> <?php echo app_lang('phone'); ?></strong>
                                        <div class="pl15"><?php echo $lead_info->phone; ?></div>
                                    </td>
                                </tr>
                            <?php } ?>
                    </table>
                </div>
                <br>
            </div>
        <?php } ?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#estimate-request-table").appTable({
            source: '<?php echo_uri("estimate_requests/estimate_request_filed_list_data/" . $model_info->id) ?>',
            order: [[1, "asc"]],
            hideTools: true,
            displayLength: 100,
            columns: [
                {title: '<?php echo app_lang("title") ?>'},
                {visible: false}
            ],
            onInitComplete: function () {
                $(".dataTables_empty").hide();
            }
        });
    });
</script>
