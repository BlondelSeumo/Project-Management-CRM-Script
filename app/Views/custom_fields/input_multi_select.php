<?php
$uid = "_" . uniqid(rand());

$options = $field_info->options ? $field_info->options : "";
$options_array = explode(",", $options);

$options_dropdown = array();
if ($options && count($options_array)) {
    foreach ($options_array as $value) {
        $options_dropdown[] = array("id" => $value, "text" => $value);
    }
} else {
    $options_dropdown = array(array("id" => "-", "text" => "-"));
}

echo form_input(array(
    "id" => "custom_field_" . $field_info->id . $uid,
    "name" => "custom_field_" . $field_info->id,
    "value" => isset($field_info->value) ? $field_info->value : "",
    "class" => "form-control validate-hidden",
    "placeholder" => $field_info->placeholder,
    "data-rule-required" => $field_info->required ? true : "false",
    "data-msg-required" => app_lang("field_required"),
    "data-custom-multi-select-input" => 1
));
?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#custom_field_<?php echo $field_info->id . $uid; ?>").select2({data:<?php echo json_encode($options_dropdown); ?>, tags: true});
    });
</script>
