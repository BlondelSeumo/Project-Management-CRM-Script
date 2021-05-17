<script>
    $(document).ready(function () {
        //load all related data of the selected project
        $("#project_id").select2().on("change", function () {
            var projectId = $(this).val();
            if ($(this).val()) {
                $('#milestone_id').select2("destroy");
                $("#milestone_id").hide();
                $('#assigned_to').select2("destroy");
                $("#assigned_to").hide();
                $('#collaborators').select2("destroy");
                $("#collaborators").hide();
                $('#project_labels').select2("destroy");
                $("#project_labels").hide();
                appLoader.show({container: "#dropdown-apploader-section", zIndex: 1});
                $.ajax({
                    url: "<?php echo get_uri('projects/get_all_related_data_of_selected_project') ?>" + "/" + projectId,
                    dataType: "json",
                    success: function (result) {
                        $("#milestone_id").show().val("");
                        $('#milestone_id').select2({data: result.milestones_dropdown});
                        $("#assigned_to").show().val("");
                        $('#assigned_to').select2({data: result.assign_to_dropdown});
                        $("#collaborators").show().val("");
                        $('#collaborators').select2({multiple: true, data: result.collaborators_dropdown});
                        $("#project_labels").show().val("");
                        $('#project_labels').select2({multiple: true, data: result.label_suggestions});
                        appLoader.hide();
                    }
                });
            }
        });

        //intialized select2 dropdown for first time
        $("#project_labels").select2({multiple: true, data: <?php echo json_encode($label_suggestions); ?>});
        $("#collaborators").select2({multiple: true, data: <?php echo json_encode($collaborators_dropdown); ?>});
        $('#milestone_id').select2({data: <?php echo json_encode($milestones_dropdown); ?>});
        $('#assigned_to').select2({data: <?php echo json_encode($assign_to_dropdown); ?>});
    });
</script>