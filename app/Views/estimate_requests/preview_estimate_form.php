<div id="page-content" class="page-wrapper clearfix">
    <div class='text-center mb15'> <?php echo anchor(get_uri("estimate_requests/edit_estimate_form/" . $model_info->id), app_lang('close_preview'), array("class" => "btn btn-default round", "title" => app_lang('close_preview'))); ?> </div>
    <?php
    echo view("estimate_requests/estimate_request_form", array("is_preview" => true));
    ?>
</div>
