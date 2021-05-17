<?php echo form_open(get_uri("tickets/save_ticket_template"), array("id" => "ticket-template-form", "class" => "general-form", "role" => "form")); ?>

<div class="modal-body clearfix">  
    <div class="container-fluid"> 
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

        <div class="form-group">
            <div class="row">
                <label for="title" class="col-md-3"><?php echo app_lang('title'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "title",
                        "name" => "title",
                        "value" => $model_info->title,
                        "class" => "form-control",
                        "placeholder" => app_lang('title'),
                        "autofocus" => true,
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="description" class="col-md-3"><?php echo app_lang('description'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_textarea(array(
                        "id" => "description",
                        "name" => "description",
                        "value" => $model_info->description,
                        "class" => "form-control",
                        "placeholder" => app_lang('description'),
                        "style" => "height: 150px;",
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                        "data-rich-text-editor" => true
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="ticket_type_id" class=" col-md-3"><?php echo app_lang('ticket_type'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown("ticket_type_id", $ticket_types_dropdown, $model_info->ticket_type_id, "class='select2'");
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="private" class="col-md-3"><?php echo app_lang('private'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_checkbox("private", "1", $model_info->private ? true : false, "id='private' class='form-check-input'");
                    ?>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#ticket-template-form").appForm({
            onSuccess: function (result) {
                $("#ticket-template-table").appTable({newData: result.data, dataId: result.id});
            }
        });

        setTimeout(function () {
            $("#title").focus();
        }, 200);

        $("#ticket-template-form .select2").select2();

    });

</script>