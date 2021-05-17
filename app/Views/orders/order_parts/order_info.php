<span style="font-size:20px; font-weight: bold;background-color: <?php echo $color; ?>; color: #fff;">&nbsp;<?php echo get_order_id($order_info->id); ?>&nbsp;</span>
<div style="line-height: 10px;"></div><?php
if (isset($order_info->custom_fields) && $order_info->custom_fields) {
    foreach ($order_info->custom_fields as $field) {
        if ($field->value) {
            echo "<span>" . $field->custom_field_title . ": " . view("custom_fields/output_" . $field->custom_field_type, array("value" => $field->value)) . "</span><br />";
        }
    }
}
?>
<span><?php echo app_lang("order_date") . ": " . format_to_date($order_info->order_date, false); ?></span>