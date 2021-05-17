<table style="width: 100%; color: #444; border-spacing: 0">
    <thead style="font-size: 14px;">
        <tr style="font-weight: bold; background-color: #f4f4f4; text-align: left">
            <th style="width: 20%; border: 1px solid #ddd; padding: 10px; border-right: none"><?php echo app_lang("id"); ?></th>
            <th style="width: 40%; border: 1px solid #ddd; padding: 10px; border-right: none"><?php echo app_lang("task"); ?></th>
            <th style="width: 40%; border: 1px solid #ddd; padding: 10px;"><?php echo app_lang("project"); ?></th>
        </tr>
    </thead>

    <tbody>
        <?php
        foreach ($tasks as $task) {
            $task_id = get_array_value($task, "task_id");
            $task_title = get_array_value($task, "task_title");
            $project_title = get_array_value($task, "project_title");
            ?>
            <tr>
                <td style="border: 1px solid #ddd; padding: 8px 10px; border-top: none; border-right: none"><?php echo anchor(get_uri("projects/task_view/$task_id"), app_lang("task") . " " . $task_id); ?></td>
                <td style="border: 1px solid #ddd; padding: 8px 10px; border-top: none; border-right: none"><?php echo $task_title; ?></td>
                <td style="border: 1px solid #ddd; padding: 8px 10px; border-top: none"><?php echo $project_title; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>