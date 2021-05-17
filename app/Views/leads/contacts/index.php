<div class="card">
    <div class="tab-title clearfix">
        <h4><?php echo app_lang('contacts'); ?></h4>
        <div class="title-button-group">
            <?php
            echo modal_anchor(get_uri("leads/add_new_contact_modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_contact'), array("class" => "btn btn-default", "title" => app_lang('add_contact'), "data-post-client_id" => $client_id));
            ?>
        </div>
    </div>

    <div class="table-responsive">
        <table id="contact-table" class="display" width="100%">            
        </table>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#contact-table").appTable({
            source: '<?php echo_uri("leads/contacts_list_data/" . $client_id) ?>',
            order: [[1, "asc"]],
            columns: [
                {title: '', "class": "w50 text-center"},
                {title: "<?php echo app_lang("name") ?>"},
                {title: "<?php echo app_lang("job_title") ?>", "class": "w15p"},
                {title: "<?php echo app_lang("email") ?>", "class": "w20p"},
                {title: "<?php echo app_lang("phone") ?>", "class": "w15p"},
                {title: 'Skype', "class": "w15p"}
<?php echo $custom_field_headers; ?>,
                {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w50"}
            ],
            printColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4, 5], '<?php echo $custom_field_headers; ?>'),
            xlsColumns: combineCustomFieldsColumns([0, 1, 2, 3, 4, 5], '<?php echo $custom_field_headers; ?>')
        });
    });
</script>