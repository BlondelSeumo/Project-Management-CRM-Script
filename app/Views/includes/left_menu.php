<div class="sidebar sidebar-off">
    <?php
    $user = $login_user->id;
    $dashboard_link = get_uri("dashboard");
    $user_dashboard = get_setting("user_" . $user . "_dashboard");
    if ($user_dashboard) {
        $dashboard_link = get_uri("dashboard/view/" . $user_dashboard);
    }
    ?>
    <a class="sidebar-toggle-btn hide" href="#">
        <i data-feather="menu" class="icon mt-1 text-off"></i>
    </a>

    <a class="sidebar-brand brand-logo" href="<?php echo $dashboard_link; ?>"><img class="dashboard-image" src="<?php echo get_logo_url(); ?>" /></a>
    <a class="sidebar-brand brand-logo-mini" href="<?php echo $dashboard_link; ?>"><img class="dashboard-image" src="<?php echo get_favicon_url(); ?>" /></a>

    <div class="sidebar-scroll">
        <ul id="sidebar-menu" class="sidebar-menu">
            <?php
            if (!$is_preview) {
                $sidebar_menu = get_active_menu($sidebar_menu);
            }

            foreach ($sidebar_menu as $main_menu) {
                if (isset($main_menu["name"])) {
                    $submenu = get_array_value($main_menu, "submenu");
                    $expend_class = $submenu ? " expand " : "";
                    $active_class = isset($main_menu["is_active_menu"]) ? "active" : "";

                    $submenu_open_class = "";
                    if ($expend_class && $active_class) {
                        $submenu_open_class = " open ";
                    }

                    $badge = get_array_value($main_menu, "badge");
                    $badge_class = get_array_value($main_menu, "badge_class");
                    $target = (isset($main_menu['is_custom_menu_item']) && isset($main_menu['open_in_new_tab']) && $main_menu['open_in_new_tab']) ? "target='_blank'" : "";
                    ?>

                    <li class="<?php echo $active_class . " " . $expend_class . " " . $submenu_open_class . " "; ?> main">
                        <a <?php echo $target; ?> href="<?php echo isset($main_menu['is_custom_menu_item']) ? $main_menu['url'] : get_uri($main_menu['url']); ?>">
                            <i data-feather="<?php echo ($main_menu['class']); ?>" class="icon"></i>
                            <span class="menu-text <?php echo isset($main_menu['custom_class']) ? $main_menu['custom_class'] : ""; ?>"><?php echo isset($main_menu['is_custom_menu_item']) ? $main_menu['name'] : app_lang($main_menu['name']); ?></span>
                            <?php
                            if ($badge) {
                                echo "<span class='badge rounded-pill $badge_class'>$badge</span>";
                            }
                            ?>
                        </a>
                        <?php
                        if ($submenu) {
                            echo "<ul>";
                            foreach ($submenu as $s_menu) {
                                if (isset($s_menu['name'])) {
                                    $sub_menu_target = (isset($s_menu['is_custom_menu_item']) && isset($s_menu['open_in_new_tab']) && $s_menu['open_in_new_tab']) ? "target='_blank'" : "";
                                    ?>
                                <li>
                                    <a <?php echo $sub_menu_target; ?> href="<?php echo isset($s_menu['is_custom_menu_item']) ? $s_menu['url'] : get_uri($s_menu['url']); ?>">
                                        <i data-feather='minus' width='12'></i>
                                        <span><?php echo isset($s_menu['is_custom_menu_item']) ? $s_menu['name'] : app_lang($s_menu['name']); ?></span>
                                    </a>
                                </li>
                                <?php
                            }
                        }
                        echo "</ul>";
                    }
                    ?>
                    </li>
                    <?php
                }
            }
            ?>
        </ul>
    </div>
</div><!-- sidebar menu end -->

<script type='text/javascript'>
    feather.replace();
</script>