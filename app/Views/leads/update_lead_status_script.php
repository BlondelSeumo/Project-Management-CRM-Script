<?php
$statuses = array();
foreach ($lead_statuses as $status) {
    $statuses[] = array("id" => $status->id, "text" => $status->title);
}
?>

<script type="text/javascript">
    $(document).ready(function () {
        $('body').on('click', '[data-act=update-lead-status]', function () {
            $(this).appModifier({
                value: $(this).attr('data-value'),
                actionUrl: '<?php echo_uri("leads/save_lead_status") ?>/' + $(this).attr('data-id'),
                select2Option: {data: <?php echo json_encode($statuses) ?>},
                onSuccess: function (response, newValue) {
                    if (response.success) {
                        $("#lead-table").appTable({newData: response.data, dataId: response.id});
                    }
                }
            });

            return false;
        });
    });
</script>