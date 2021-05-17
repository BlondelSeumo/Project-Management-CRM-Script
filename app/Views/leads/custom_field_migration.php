<?php if (isset($custom_fields) && $custom_fields) { ?>
    <div class="form-group">
        <div class="row">
            <label class="<?php echo $label_column; ?>"><?php echo app_lang('custom_field_migration'); ?></label>
            <div class="<?php echo $field_column; ?> custom-field-migration-fields">
                <div class="pb10">
                    <?php echo form_checkbox("merge_custom_fields-$model_info->id", "1", true, "id='merge_custom_fields-$model_info->id' class='form-check-input'"); ?> 
                    <label for="merge_custom_fields-<?php echo $model_info->id; ?>"><?php echo app_lang('merge_custom_fields'); ?></label>
                    <span class="help" data-container="body" data-bs-toggle="tooltip" title="<?php echo sprintf(app_lang('merge_custom_fields_help_message'), app_lang($to_custom_field_type), app_lang($to_custom_field_type)); ?>"><span data-feather="help-circle" class="icon-16"></span></span>
                </div>

                <div class="pb10">
                    <?php echo form_checkbox("do_not_merge-$model_info->id", "1", false, "id='do_not_merge-$model_info->id' class='form-check-input'"); ?> 
                    <label for="do_not_merge-<?php echo $model_info->id; ?>"><?php echo app_lang('do_not_merge'); ?></label>
                </div>
            </div>
        </div>
    </div>
<?php } ?>