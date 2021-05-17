<li class="js-leads-cookie-tab <?php echo ($active_tab == 'leads_list') ? 'active' : ''; ?>" data-tab="leads_list"><a href="<?php echo_uri('leads'); ?>"><?php echo app_lang("list"); ?></a></li>
<li class="js-leads-cookie-tab <?php echo ($active_tab == 'leads_kanban') ? 'active' : ''; ?>" data-tab="leads_kanban"><a href="<?php echo_uri('leads/all_leads_kanban/'); ?>" ><?php echo app_lang('kanban'); ?></a></li>

<script>
    var selectedTab = getCookie("selected_leads_tab_" + "<?php echo $login_user->id; ?>");

    if (selectedTab && selectedTab !== "<?php echo $active_tab ?>" && selectedTab === "leads_kanban") {
        window.location.href = "<?php echo_uri('leads/all_leads_kanban'); ?>";
    }

    //save the selected tab in browser cookie
    $(document).ready(function () {
        $(".js-leads-cookie-tab").click(function () {
            var tab = $(this).attr("data-tab");
            if (tab) {
                setCookie("selected_leads_tab_" + "<?php echo $login_user->id; ?>", tab);
            }
        });
    });
</script>