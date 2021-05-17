<script>

    //we have to add values of selected tasks for batch operation
    batchTaskIds = [];

    //hide change status button and marked-checkboxes
    function hideBatchTasksBtn(isGlobalTasksList) {
        $(".batch-cancel-btn").addClass("hide");

        //don't show active button while it's from global tasks list view
        if (!isGlobalTasksList && ($("[name='project_id']").val() || $(".batch-update-btn").attr("data-post-project_id"))) {
            $(".batch-active-btn").removeClass("hide");
        }

        $(".batch-update-btn").addClass("hide");
        $(".batch-update-btn").removeAttr("data-action-url");

        $(".batch-update-header").remove();
        $(".td-checkbox").remove();

        $("[data-act=update-task-status-checkbox]").find("span").removeClass("checkbox-checked");
        $("[data-act=batch-operation-task-checkbox]").removeClass("checkbox-checked-sm");
        $("[data-act=batch-operation-task-checkbox]").find("span").removeClass("checkbox-checked-sm");
        $(".kanban-item").removeClass("kanban-item-checked");

        $(".checkbox-blank-sm").each(function () {
            $(this).find("svg").remove();
        });

        batchTaskIds = [];
    }

    $(document).ready(function () {
        var $batchActiveBtn = $(".batch-active-btn"),
                $batchUpdateBtn = $(".batch-update-btn"),
                $batchCancelBtn = $(".batch-cancel-btn");

        //active batch operation of tasks
        $('body').on('click', '.batch-active-btn', function () {
            var dom = "<td class='td-checkbox' style='padding: 0 !important'><a data-act='batch-operation-task-checkbox'><span class='checkbox-blank'></span></a></td>";

            $("#task-table thead tr").prepend("<th class='batch-update-header text-center'>-</th>");
            $(".js-task").closest("tr").prepend(dom);
            $(this).addClass("hide");
            $(this).closest(".title-button-group").find(".batch-cancel-btn").removeClass("hide");
        });

        //cancel batch operation of tasks
        $('body').on('click', '.batch-cancel-btn', function () {
            hideBatchTasksBtn();
            batchTaskIds = [];
        });

        //show batch update button after selecting project on global tasks
        $('body').on('change', "[name='project_id']", function () {
            var projectId = $(this).val();

            if (projectId) {
                //check user's permission
                $.ajax({
                    url: '<?php echo_uri("projects/can_edit_task_of_the_project") ?>' + '/' + projectId,
                    dataType: 'json',
                    cache: false,
                    success: function (response) {
                        if (response.success) {
                            $batchActiveBtn.removeClass("hide");
                            $batchUpdateBtn.attr("data-post-project_id", projectId);
                        } else {
                            hideBatchActiveBtn();
                        }
                    }
                });
            } else {
                hideBatchActiveBtn();
            }

            var hideBatchActiveBtn = function () {
                $batchActiveBtn.addClass("hide");
                $batchUpdateBtn.removeAttr("data-post-project_id");
            };
        });

        $('body').on('click', '[data-act=batch-operation-task-checkbox]', function (e) {
            if ($(this).closest(".tab-pane").find(".batch-update-btn").attr("class")) {
                //it's from project's details view
                //select the closest buttons
                $batchUpdateBtn = $(this).closest(".tab-pane").find(".batch-update-btn");
                $batchCancelBtn = $(this).closest(".tab-pane").find(".batch-cancel-btn");
            }

            var checkbox = $(this).find("span"),
                    task_id = $(this).closest("tr").find(".js-task").attr("data-id"),
                    checkbox_checked_class = "checkbox-checked",
                    is_kanban = false,
                    selectedProject = $batchUpdateBtn.attr("data-post-project_id");

            //we have to check if the task is from kanban list
            if ($(this).closest("li").hasClass("kanban-col")) {
                task_id = $(this).closest("a").attr("data-id");
                checkbox_checked_class = "checkbox-checked-sm";
                checkbox = $(this);
                is_kanban = true;

                //stop the default modal anchor action
                e.stopPropagation();
                e.preventDefault();
            }

            checkbox.addClass("inline-loader");

            //there are two operation
            if ($.inArray(task_id, batchTaskIds) !== -1) {
                //if there is already added the task to tasks list
                var index = batchTaskIds.indexOf(task_id);
                batchTaskIds.splice(index, 1);
                checkbox.removeClass(checkbox_checked_class);

                if (is_kanban) {
                    $(this).closest("a").removeClass("kanban-item-checked");
                    checkbox.html("");
                }
            } else {
                //if it's new item to add to tasks list
                batchTaskIds.push(task_id);
                checkbox.addClass(checkbox_checked_class);

                if (is_kanban) {
                    $(this).closest("a").addClass("kanban-item-checked");
                    checkbox.html("<i data-feather='check'></i>");
                    feather.replace();
                }
            }

            checkbox.removeClass("inline-loader");

            if (batchTaskIds.length && selectedProject) {
                $batchUpdateBtn.removeClass("hide");
                if (is_kanban) {
                    $batchCancelBtn.removeClass("hide");
                }
            } else {
                $batchUpdateBtn.addClass("hide");
                if (is_kanban) {
                    $batchCancelBtn.addClass("hide");
                }
            }

            var serializeOfArray = batchTaskIds.join("-");

            $batchUpdateBtn.attr("data-action-url", "<?php echo_uri("projects/batch_update_modal_form/"); ?>" + serializeOfArray);

        });
    });
</script>