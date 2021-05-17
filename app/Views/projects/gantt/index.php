<?php
if (!$project_id) {
    load_css(array(
        "assets/js/gantt-chart/frappe-gantt.css"
    ));
    load_js(array(
        "assets/js/gantt-chart/frappe-gantt.js"
    ));
    echo "<div id='page-content' class='page-wrapper clearfix'>";
}
?>
<div class="card">
    <div class="tab-title clearfix">
        <h4><?php echo app_lang('gantt'); ?></h4>
        <div class="float-end p10 mr10 custom-toolbar">
            <?php
            if ($show_project_members_dropdown) {
                echo app_lang("group_by") . " : ";
                $milestones_and_members_group_by = array("milestones" => app_lang("milestones"), "members" => app_lang("team_members"));

                $project_group_by = array();
                if (!$project_id) {
                    $project_group_by = array("projects" => app_lang("projects"));
                }

                $gantt_group_by = array_merge($milestones_and_members_group_by, $project_group_by);

                echo form_dropdown("gantt-group-by", $gantt_group_by, array(), "class='select2 w200 mr10 reload-gantt' id='gantt-group-by'");
            }
            ?>
            <?php
            if (!$project_id) {
                echo form_input(array(
                    "id" => "gantt-projects-dropdown",
                    "name" => "gantt-projects-dropdown",
                    "class" => "select2 w200 reload-gantt"
                ));
            }
            ?>
            <?php
            if ($show_project_members_dropdown) {
                echo form_input(array(
                    "id" => "gantt-members-dropdown",
                    "name" => "gantt-members-dropdown",
                    "class" => "select2 w200 reload-gantt"
                ));
            }
            ?>
            <?php
            echo form_input(array(
                "id" => "gantt-milestone-dropdown",
                "name" => "gantt-milestone-dropdown",
                "class" => "select2 w200 reload-gantt"
            ));
            ?>

            <input type="hidden" name="gantt-status-dropdown" id="gantt-status-dropdown" class="reload-gantt" />
            <span class='dropdown-apploader-section ml10 inline-block gantt-status-filter' id="gantt-status-dropdown-container"></span>

            <?php
            $gantt_view_dropdown = array(
                array("id" => "Day", "text" => app_lang("days_view")),
                array("id" => "Week", "text" => app_lang("weeks_view")),
                array("id" => "Month", "text" => app_lang("months_view"))
            );

            helper('cookie');

            echo form_input(array(
                "id" => "gantt-view-dropdown",
                "name" => "gantt-view-dropdown",
                "class" => "select2 w200 ml10",
                "value" => get_cookie("gantt_view_of_user_" . $login_user->id) ? get_cookie("gantt_view_of_user_" . $login_user->id) : "Day"
            ));
            ?>
        </div>
    </div>
    <div class="w100p">
        <div id="gantt-chart" style="width: 100%;"></div>
    </div>

</div>
<?php
if (!$project_id) {
    echo "</div>";
}

echo modal_anchor(get_uri("projects/task_view"), "", array("id" => "show_task_hidden", "class" => "hide", "data-modal-lg" => "1"));
?>

<script type="text/javascript">
    var loadGantt = function (group_by, milestoneId, userId, status, projectId, scrollToLast) {
        group_by = group_by || "milestones";
        milestoneId = milestoneId || "0";
        userId = userId || "0";
        status = status || "";
        projectId = projectId || "<?php echo $project_id; ?>";

        var scrollLeft = $("#gantt-chart .gantt-container").scrollLeft();

        $("#gantt-chart").html("<div style='height:100px;'></div>");
        appLoader.show({container: "#gantt-chart", css: "right:50%;"});

        $.ajax({
            url: "<?php echo get_uri("projects/gantt_data/"); ?>" + projectId + "/" + group_by + "/" + milestoneId + "/" + userId + "/" + status,
            type: 'POST',
            dataType: 'json',
            success: function (result) {
                appLoader.hide();
                if (!result.length) {
                    $("#gantt-chart").html("<div class='text-off text-center' style='padding: 41px;'><?php echo app_lang("no_result_found"); ?></div>");
                    return;
                }

                $("#gantt-chart").html("");

                var viewMode = "<?php echo get_cookie("gantt_view_of_user_" . $login_user->id) ? get_cookie("gantt_view_of_user_" . $login_user->id) : 'Day'; ?>";

                var gantt = new Gantt("#gantt-chart", result, {
                    language: "custom",
                    month_languages: AppLanugage.months,
                    popup_trigger: "mouseover",
                    view_mode: viewMode,
                    on_click: function (task) {
                        if (task.dependencies.length) {
                            $("#show_task_hidden").attr("data-post-id", task.id);
                            $("#show_task_hidden").attr("data-title", "<?php echo app_lang('task_info') . " #" ?>" + task.id);
                            $("#show_task_hidden").trigger("click");
                        } else {
                            collapseScrollLeft = $("#gantt-chart .gantt-container").scrollLeft();
                            gantt.collapse_group(task.id);
                            $("#gantt-chart .gantt-container").scrollLeft(collapseScrollLeft);
                        }
                    },
                    on_date_change: function (task, start, end) {
                        appLoader.show();

                        var data = {
                            start_date: moment(start, "YYYY-MM-DD").format("YYYY-MM-DD"),
                            deadline: moment(end, "YYYY-MM-DD").format("YYYY-MM-DD"),
                            task_id: task.id
                        };

                        $.ajax({
                            url: "<?php echo get_uri('projects/save_gantt_task_date') ?>",
                            type: 'POST',
                            dataType: 'json',
                            data: data,
                            success: function (result) {
                                appLoader.hide();
                                if (!result.success) {
                                    appAlert.error(result.message);
                                }
                            }
                        });
                    },
                    custom_popup_html: function (task) {
                        var dateFormat = getJsDateFormat().toUpperCase(),
                                start = moment(task._start, "YYYY-MM-DD"),
                                end = moment(task._end, "YYYY-MM-DD"),
                                startDate = start.format(dateFormat),
                                endDate = end.subtract(1, 'days').format(dateFormat), //it's giving unnecessarily 1 extra day
                                daysCount = Math.abs(start.startOf('day').diff(end.startOf('day'), 'days')) + 1;

                        if (daysCount) {
                            if (daysCount === 1) {
                                daysCount = daysCount + " <?php echo app_lang("day"); ?>";
                            } else {
                                daysCount = daysCount + " <?php echo app_lang("days"); ?>";
                            }
                        }

                        return `
                            <div class="gantt-task-popup">
                                <div class="mb5">
                                    <strong>${task.name}</strong>
                                </div>
                                <div><strong><?php echo app_lang("start_date"); ?>: </strong> ${startDate}</div>
                                <div><strong><?php echo app_lang("deadline"); ?>: </strong> ${endDate}</div>
                                <div><strong><?php echo app_lang("total"); ?>: </strong> ${daysCount}</div>
                            </div>
                        `;
                    }
                });

                //change view mode
                var $ganttView = $("#gantt-view-dropdown");

                $ganttView.on("change", function () {
                    var type = $(this).val();
                    changeGanttView(type);

                    //save cookie
                    setCookie("gantt_view_of_user_<?php echo $login_user->id; ?>", type);
                });

                function changeGanttView(type) {
                    gantt.change_view_mode(type);
                }

                if (scrollToLast && scrollLeft) {
                    setTimeout(function () {
                        $("#gantt-chart .gantt-container").animate({scrollLeft: scrollLeft}, 'slow');
                    }, 500);
                }
            }
        });
    };

    var $ganttGroupBy = $("#gantt-group-by"),
            $ganttProjects = $("#gantt-projects-dropdown"),
            $ganttMilestone = $("#gantt-milestone-dropdown"),
            $ganttMembers = $("#gantt-members-dropdown"),
            $ganttStatus = $("#gantt-status-dropdown"),
            $ganttView = $("#gantt-view-dropdown");

    window.reloadGantt = function (scrollToLast) {
        var group_by = $ganttGroupBy.val() || "milestones" || "projects",
                milestoneId = $ganttMilestone.val(),
                userId = $("#gantt-members-dropdown").val(),
                status = $ganttStatus.val();

        var projectId = $ganttProjects.val() || "<?php echo $project_id; ?>";

        loadGantt(group_by, milestoneId, userId, status, projectId, scrollToLast);
    }

    $(document).ready(function () {
        $ganttGroupBy.select2();

        $ganttMilestone.select2({
            data: <?php echo $milestone_dropdown; ?>
        });

        $ganttView.select2({
            data: <?php echo json_encode($gantt_view_dropdown); ?>
        });

        if ($ganttMembers.length) {
            $ganttMembers.select2({
                data: <?php echo $project_members_dropdown; ?>
            });
        }

        $("#gantt-status-dropdown-container").appMultiSelect({
            text: "<?php echo app_lang('status'); ?>",
            options: <?php echo $status_dropdown; ?>,
            onChange: function (values) {
                $ganttStatus.val(values.join('-'));
                reloadGantt();
            }
        });

<?php if (!$project_id) { ?>
            $ganttProjects.select2({
                data: <?php echo $projects_dropdown; ?>
            });
<?php } ?>

        //refresh milestones on changing of project
        $ganttProjects.on("change", function () {
            var projectId = $(this).val();
            if (projectId) {
                $ganttMilestone.select2("destroy");
                $ganttMilestone.hide();
                appLoader.show();
                $.ajax({
                    url: "<?php echo get_uri('projects/get_milestones_for_filter') ?>",
                    dataType: "json",
                    type: 'POST',
                    data: {project_id: projectId},
                    success: function (result) {
                        $ganttMilestone.show().val("");
                        $ganttMilestone.select2({data: result});
                        appLoader.hide();
                    }
                });
            }
        });

        //this should be under the milestone changing codes according to projects
        $(".reload-gantt").change(function () {
            reloadGantt();
        });

        loadGantt();
    });
</script>