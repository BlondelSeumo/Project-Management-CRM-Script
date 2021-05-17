<?php echo form_open(get_uri("leads/save_contact"), array("id" => "contact-form", "class" => "general-form", "role" => "form", "autocomplete" => "false")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <?php echo view("leads/contacts/contact_general_info_fields"); ?>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#contact-form").appForm({
            onSuccess: function (result) {
                $("#contact-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        setTimeout(function () {
            $("#first_name").focus();
        }, 200);
    });
</script>    