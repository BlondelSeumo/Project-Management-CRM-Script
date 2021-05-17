<div class="card clearfix rounded-0 <?php
if (isset($page_type) && $page_type === "full") {
    echo "m20";
}
?>">
    <ul id="team-member-leave-tabs" data-bs-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
        <li class="title-tab"><h4 class="pl15 pt10 pr15">
                <?php
                if ($login_user->id === $applicant_id) {
                    echo app_lang("my_leave");
                } else {
                    echo app_lang("leaves");
                }
                ?>
            </h4>
        </li>
        <li><a id="monthly-leaves-button"  role="presentation"  href="javascript:;" data-bs-target="#team_member-monthly-leaves"><?php echo app_lang("monthly"); ?></a></li>
        <li><a role="presentation" href="<?php echo_uri("team_members/yearly_leaves/"); ?>" data-bs-target="#team_member-yearly-leaves"><?php echo app_lang('yearly'); ?></a></li>
        <?php if ($login_user->id === $applicant_id) { ?>
            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    <?php echo modal_anchor(get_uri('leaves/apply_leave_modal_form'), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('apply_leave'), array("class" => "btn btn-default", "title" => app_lang('apply_leave'))); ?>
                </div>
            </div>    
        <?php } ?>

    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade" id="team_member-monthly-leaves">
            <div class="table-responsive">
                <table id="monthly-leaves-table" class="display" cellspacing="0" width="100%">            
                </table>
            </div>
            <script type="text/javascript">
                loadMembersLeavesTable = function (selector, dateRange) {
                    $(selector).appTable({
                        source: '<?php echo_uri("leaves/all_application_list_data") ?>',
                        dateRangeType: dateRange,
                        filterParams: {applicant_id: "<?php echo $applicant_id; ?>"},
                        columns: [
                            {targets: [1], visible: false, searchable: false},
                            {title: '<?php echo app_lang("leave_type") ?>'},
                            {title: '<?php echo app_lang("date") ?>', "class": "w20p"},
                            {title: '<?php echo app_lang("duration") ?>', "class": "w20p"},
                            {title: '<?php echo app_lang("status") ?>', "class": "w15p"},
                            {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
                        ],
                        printColumns: [1, 2, 3, 4],
                        xlsColumns: [1, 2, 3, 4]
                    });
                };

                $(document).ready(function () {
                    loadMembersLeavesTable("#monthly-leaves-table", "monthly");
                });
            </script>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="team_member-yearly-leaves"></div>
    </div>
</div>