<div class="list-group">
    <?php
    if (count($clients)) {
        foreach ($clients as $client) {

            $icon = "briefcase";

            $title = "<i data-feather='$icon' class='icon-16 mr10'></i> " . $client->company_name;
            echo anchor(get_uri("clients/view/" . $client->id), $title, array("class" => "dropdown-item"));
        }
    } else {
        ?>
        <div class='list-group-item'>
            <?php echo app_lang("empty_starred_clients"); ?>              
        </div>
    <?php } ?>
</div>