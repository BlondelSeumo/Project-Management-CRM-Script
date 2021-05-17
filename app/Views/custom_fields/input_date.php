<?php
$uid = "_" . uniqid(rand());

echo form_input(array(
    "id" => "custom_field_" . $field_info->id . $uid,
    "name" => "custom_field_" . $field_info->id,
    "value" => isset($field_info->value) ? $field_info->value : "",
    "class" => "form-control",
    "placeholder" => $field_info->placeholder,
    "data-rule-required" => $field_info->required ? true : "false",
    "data-msg-required" => app_lang("field_required")
));
?>


<script type="text/javascript">
    $(document).ready(function () {
        setDatePicker("#<?php echo "custom_field_" . $field_info->id . $uid; ?>");
    });
</script>
