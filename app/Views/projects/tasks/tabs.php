<li class="js-cookie-tab <?php echo ($active_tab == 'tasks_list') ? 'active' : ''; ?>" data-tab="tasks_list"><a href="<?php echo_uri('projects/all_tasks/'); ?>"><?php echo app_lang("list"); ?></a></li>
<li class="js-cookie-tab <?php echo ($active_tab == 'tasks_kanban') ? 'active' : ''; ?>" data-tab="tasks_kanban"><a href="<?php echo_uri('projects/all_tasks_kanban/'); ?>" ><?php echo app_lang('kanban'); ?></a></li>

<script>
    var selectedTab = getCookie("selected_tab_" + "<?php echo $login_user->id; ?>");

    if (selectedTab && selectedTab !== "<?php echo $active_tab ?>" && selectedTab === "tasks_kanban") {
        window.location.href = "<?php echo_uri('projects/all_tasks_kanban'); ?>";
    }

    //save the selected tab in browser cookie
    $(document).ready(function () {
        $(".js-cookie-tab").click(function () {
            var tab = $(this).attr("data-tab");
            if (tab) {
                setCookie("selected_tab_" + "<?php echo $login_user->id; ?>", tab);
            }
        });
    });
</script>