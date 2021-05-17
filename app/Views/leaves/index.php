<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h1><?php echo app_lang('leaves'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("leaves/apply_leave_modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('apply_leave'), array("class" => "btn btn-default", "title" => app_lang('apply_leave'))); ?>
                <?php echo modal_anchor(get_uri("leaves/assign_leave_modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('assign_leave'), array("class" => "btn btn-default", "title" => app_lang('assign_leave'))); ?>
            </div>
        </div>
        <ul id="leaves-tabs" data-bs-toggle="ajax-tab" class="nav nav-tabs bg-white inner" role="tablist">
            <li><a  role="presentation"  href="<?php echo_uri("leaves/pending_approval/"); ?>" data-bs-target="#leave-pending-approval"><?php echo app_lang("pending_approval"); ?></a></li>
            <li><a  role="presentation" href="<?php echo_uri("leaves/all_applications/"); ?>" data-bs-target="#leave-all-applications"><?php echo app_lang("all_applications"); ?></a></li>
            <li><a  role="presentation" href="<?php echo_uri("leaves/summary/"); ?>" data-bs-target="#leave-summary"><?php echo app_lang("summary"); ?></a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade active" id="leave-pending-approval"></div>
            <div role="tabpanel" class="tab-pane fade" id="leave-all-applications"></div>
            <div role="tabpanel" class="tab-pane fade" id="leave-summary"></div>
        </div>
    </div>
</div>