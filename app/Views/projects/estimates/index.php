<div class="bg-white p10 mb20">
    <i data-feather="link" class="icon-16"></i>
    <?php echo (anchor(get_uri("estimates/view/" . $project_info->estimate_id), get_estimate_id($project_info->estimate_id))); ?>
</div>