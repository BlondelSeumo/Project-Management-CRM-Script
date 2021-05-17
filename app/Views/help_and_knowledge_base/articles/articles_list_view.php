<h2> <?php echo $category_info->title; ?></h2>
<p class="mb20"><?php echo nl2br($category_info->description); ?></p>
<?php
foreach ($articles as $article) {

    echo anchor(get_uri($category_info->type . "/view/" . $article->id), "<i data-feather='arrow-right-circle' class='icon-16 mr15'></i>" . $article->title, array("class" => "list-group-item"));
}
?>
<div class="mb20"></div>
