<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h1><?php echo app_lang('team_members'); ?></h1>
            <div class="title-button-group">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-default btn-sm active me-0"  title="<?php echo app_lang('list_view'); ?>"><i data-feather="menu" class="icon-16"></i></button>
                    <?php echo anchor(get_uri("team_members/view"), "<i data-feather='grid' class='icon-16'></i>", array("class" => "btn btn-default btn-sm")); ?>
                </div>
                <?php
                if ($login_user->is_admin) {
                    echo modal_anchor(get_uri("team_members/invitation_modal"), "<i data-feather='mail' class='icon-16'></i> " . app_lang('send_invitation'), array("class" => "btn btn-default", "title" => app_lang('send_invitation')));
                    echo modal_anchor(get_uri("team_members/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_team_member'), array("class" => "btn btn-default", "title" => app_lang('add_team_member')));
                }
                ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="team_member-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var visibleContact = false;
        if ("<?php echo $show_contact_info; ?>") {
            visibleContact = true;
        }

        var visibleDelete = false;
        if ("<?php echo $login_user->is_admin; ?>") {
            visibleDelete = true;
        }

        $("#team_member-table").appTable({
            source: '<?php echo_uri("team_members/list_data/") ?>',
            order: [[1, "asc"]],
            radioButtons: [{text: '<?php echo app_lang("active_members") ?>', name: "status", value: "active", isChecked: true}, {text: '<?php echo app_lang("inactive_members") ?>', name: "status", value: "inactive", isChecked: false}],
            columns: [
                {title: '', "class": "w50 text-center"},
                {title: "<?php echo app_lang("name") ?>"},
                {title: "<?php echo app_lang("job_title") ?>", "class": "w15p"},
                {visible: visibleContact, title: "<?php echo app_lang("email") ?>", "class": "w20p"},
                {visible: visibleContact, title: "<?php echo app_lang("phone") ?>", "class": "w15p"}
<?php echo $custom_field_headers; ?>,
                {visible: visibleDelete, title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
            ],
            printColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4], '<?php echo $custom_field_headers; ?>'),
            xlsColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4], '<?php echo $custom_field_headers; ?>')

        });
    });
</script>    
