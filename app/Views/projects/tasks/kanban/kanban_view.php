<div id="kanban-wrapper">
    <?php
    $columns_data = array();

    foreach ($tasks as $task) {

        $exising_items = get_array_value($columns_data, $task->status_id);
        if (!$exising_items) {
            $exising_items = "";
        }

        $task_labels = make_labels_view_data($task->labels_list);
        $sub_task_icon = "";
        if ($task->parent_task_id) {
            $sub_task_icon = "<span class='sub-task-icon mr5' title='" . app_lang("sub_task") . "'><i data-feather='git-merge' class='icon-14'></i></span>";
        }

        if ($task_labels) {
            $task_labels = "<div class='meta'>$task_labels</div>";
        }

        $unread_comments_class = "";
        if (isset($task->unread) && $task->unread && $task->unread != "0") {
            $unread_comments_class = "unread-comments-of-kanban unread";
        }

        $batch_operation_checkbox = "";
        if ($login_user->user_type == "staff" && $can_edit_tasks && $project_id) {
            $batch_operation_checkbox = "<span data-act='batch-operation-task-checkbox' title='" . app_lang("batch_update") . "' class='checkbox-blank-sm float-end invisible'></span>";
        }

        $disable_dragging = can_edit_this_task_status($task->assigned_to) ? "" : "disable-dragging";

        $item = $exising_items . modal_anchor(get_uri("projects/task_view"), "<span class='avatar'>" .
                        "<img src='" . get_avatar($task->assigned_to_avatar) . "'>" .
                        "</span>" . $sub_task_icon . $task->id . ". " . $task->title . $batch_operation_checkbox .
                        $task_labels, array("class" => "kanban-item $disable_dragging $unread_comments_class", "data-id" => $task->id, "data-project_id" => $task->project_id, "data-sort" => $task->new_sort, "data-post-id" => $task->id, "title" => app_lang('task_info') . " #$task->id", "data-modal-lg" => "1"));

        $columns_data[$task->status_id] = $item;
    }
    ?>

    <ul id="kanban-container" class="kanban-container clearfix">

        <?php foreach ($columns as $column) { ?>
            <li class="kanban-col" >
                <div class="kanban-col-title" style="border-bottom: 3px solid <?php echo $column->color ? $column->color : "#2e4053"; ?>;"> <?php echo $column->key_name ? app_lang($column->key_name) : $column->title; ?> </div>

                <div class="kanban-input general-form hide">
                    <?php
                    echo form_input(array(
                        "id" => "title",
                        "name" => "title",
                        "value" => "",
                        "class" => "form-control",
                        "placeholder" => app_lang('add_a_task')
                    ));
                    ?>
                </div>

                <div  id="kanban-item-list-<?php echo $column->id; ?>" class="kanban-item-list" data-status_id="<?php echo $column->id; ?>">
                    <?php echo get_array_value($columns_data, $column->id); ?>
                </div>
            </li>
        <?php } ?>

    </ul>
</div>

<img id="move-icon" class="hide" src="<?php echo get_file_uri("assets/images/move.png"); ?>" alt="..." />

<script type="text/javascript">
    var kanbanContainerWidth = "";

    adjustViewHeightWidth = function () {

        if (!$("#kanban-container").length) {
            return false;
        }


        var totalColumns = "<?php echo $total_columns ?>";
        var columnWidth = (335 * totalColumns) + 5;

        if (columnWidth > kanbanContainerWidth) {
            $("#kanban-container").css({width: columnWidth + "px"});
        } else {
            $("#kanban-container").css({width: "100%"});
        }


        //set wrapper scroll
        if ($("#kanban-wrapper")[0].offsetWidth < $("#kanban-wrapper")[0].scrollWidth) {
            $("#kanban-wrapper").css("overflow-x", "scroll");
        } else {
            $("#kanban-wrapper").css("overflow-x", "hidden");
        }


        //set column scroll

        var columnHeight = $(window).height() - $(".kanban-item-list").offset().top - 30;
        if (isMobile()) {
            columnHeight = $(window).height() - 30;
        }

        $(".kanban-item-list").height(columnHeight);

        $(".kanban-item-list").each(function (index) {

            //set scrollbar on column... if requred
            if ($(this)[0].offsetHeight < $(this)[0].scrollHeight) {
                $(this).css("overflow-y", "scroll");
            } else {
                $(this).css("overflow-y", "hidden");
            }

        });
    };


    saveStatusAndSort = function ($item, status) {
        appLoader.show();
        adjustViewHeightWidth();

        var $prev = $item.prev(),
                $next = $item.next(),
                prevSort = 0, nextSort = 0, newSort = 0,
                step = 100000, stepDiff = 500,
                id = $item.attr("data-id"),
                project_id = $item.attr("data-project_id");

        if ($prev && $prev.attr("data-sort")) {
            prevSort = $prev.attr("data-sort") * 1;
        }

        if ($next && $next.attr("data-sort")) {
            nextSort = $next.attr("data-sort") * 1;
        }


        if (!prevSort && nextSort) {
            //item moved at the top
            newSort = nextSort - stepDiff;

        } else if (!nextSort && prevSort) {
            //item moved at the bottom
            newSort = prevSort + step;

        } else if (prevSort && nextSort) {
            //item moved inside two items
            newSort = (prevSort + nextSort) / 2;

        } else if (!prevSort && !nextSort) {
            //It's the first item of this column
            newSort = step * 100; //set a big value for 1st item
        }

        $item.attr("data-sort", newSort);


        $.ajax({
            url: '<?php echo_uri("projects/save_task_sort_and_status") ?>',
            type: "POST",
            data: {id: id, sort: newSort, status_id: status, project_id: project_id},
            success: function () {
                appLoader.hide();

                if (isMobile()) {
                    adjustViewHeightWidth();
                }
            }
        });

    };



    $(document).ready(function () {
        kanbanContainerWidth = $("#kanban-container").width();

        if (isMobile() && window.scrollToKanbanContent) {
            window.scrollTo(0, 220); //scroll to the content for mobile devices
            window.scrollToKanbanContent = false;
        }

        var isChrome = !!window.chrome && !!window.chrome.webstore;


<?php if ($login_user->user_type == "staff" || ($login_user->user_type == "client" && $can_edit_tasks)) { ?>
            $(".kanban-item-list").each(function (index) {
                var id = this.id;

                var options = {
                    animation: 150,
                    group: "kanban-item-list",
                    filter: ".disable-dragging",
                    cancel: ".disable-dragging",
                    onAdd: function (e) {
                        //moved to another column. update bothe sort and status
                        saveStatusAndSort($(e.item), $(e.item).closest(".kanban-item-list").attr("data-status_id"));
                    },
                    onUpdate: function (e) {
                        //updated sort
                        saveStatusAndSort($(e.item));
                    }
                };

                //apply only on chrome because this feature is not working perfectly in other browsers.
                if (isChrome) {
                    options.setData = function (dataTransfer, dragEl) {
                        var img = document.createElement("img");
                        img.src = $("#move-icon").attr("src");
                        img.style.opacity = 1;
                        dataTransfer.setDragImage(img, 5, 10);
                    };

                    options.ghostClass = "kanban-sortable-ghost";
                    options.chosenClass = "kanban-sortable-chosen";
                }

                Sortable.create($("#" + id)[0], options);
            });
<?php } ?>


        adjustViewHeightWidth();



    });

    $(window).resize(function () {
        adjustViewHeightWidth();
    });

</script>

<?php echo view("projects/tasks/update_task_read_comments_status_script"); ?>