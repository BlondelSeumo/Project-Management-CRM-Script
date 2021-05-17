<?php
$exclude_link = explode(":", $value);

if (is_array($exclude_link)) {
    if (get_array_value($exclude_link, 0) != "http" && get_array_value($exclude_link, 0) != "https") {
        $value = "http://" . $value;
    }
}
?>

<a target='_blank' href='<?php echo $value; ?>'><?php echo $value; ?></a>