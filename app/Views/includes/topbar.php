<?php load_js(array("assets/js/push_notification/pusher/pusher.min.js")); ?>

<?php $user = $login_user->id; ?>

<nav class="navbar navbar-expand fixed-top navbar-light navbar-custom shadow-sm" role="navigation" id="default-navbar">
    <div class="container-fluid">
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link sidebar-toggle-btn" aria-current="page" href="#">
                        <i data-feather="menu" class="icon"></i>
                    </a>
                </li>

                <?php
                //get the array of hidden topbar menus
                $hidden_topbar_menus = explode(",", get_setting("user_" . $user . "_hidden_topbar_menus"));

                if (!in_array("to_do", $hidden_topbar_menus)) {
                    echo view("todo/topbar_icon");
                }
                if (!in_array("favorite_projects", $hidden_topbar_menus) && !(get_setting("disable_access_favorite_project_option_for_clients") && $login_user->user_type == "client")) {
                    echo view("projects/star/topbar_icon");
                }
                if (!in_array("favorite_clients", $hidden_topbar_menus)) {
                    echo view("clients/star/topbar_icon");
                }
                if (!in_array("dashboard_customization", $hidden_topbar_menus) && (get_setting("disable_new_dashboard_icon") != 1)) {
                    echo view("dashboards/list/topbar_icon");
                }
                ?>

                <?php
                if (has_my_open_timers()) {
                    echo view("projects/open_timers_topbar_icon");
                }
                ?>
            </ul>

            <div class="d-flex w-auto">
                <ul class="navbar-nav">

                    <?php
                    if ($login_user->user_type == "staff") {
                        load_js(array("assets/js/awesomplete/awesomplete.min.js"));
                        ?>
                        <li class="nav-item hidden-sm" title="<?php echo app_lang('search') . ' (/)'; ?>" data-bs-toggle="tooltip" data-placement="left">
                            <?php echo modal_anchor(get_uri("search/search_modal_form"), "<i data-feather='search' class='icon'></i>", array("class" => "nav-link", "data-modal-title" => app_lang('search') . ' (/)', "data-post-hide-header" => true, "id" => "global-search-btn")); ?>
                        </li>
                    <?php } ?>

                    <?php
                    if (!in_array("quick_add", $hidden_topbar_menus)) {
                        echo view("settings/topbar_parts/quick_add");
                    }
                    ?>

                    <?php if (!in_array("language", $hidden_topbar_menus) && (($login_user->user_type == "staff" && !get_setting("disable_language_selector_for_team_members")) || ($login_user->user_type == "client" && !get_setting("disable_language_selector_for_clients")))) { ?>

                        <li class="nav-item dropdown">
                            <?php echo js_anchor("<i data-feather='globe' class='icon'></i>", array("id" => "personal-language-icon", "class" => "nav-link dropdown-toggle", "data-bs-toggle" => "dropdown")); ?>

                            <ul class="dropdown-menu dropdown-menu-end language-dropdown">
                                <li>
                                    <?php
                                    $user_language = get_setting("user_" . $login_user->id . "_personal_language");
                                    $system_language = get_setting("language");

                                    foreach (get_language_list() as $language) {
                                        $language_status = "";
                                        $language_text = $language;

                                        if ($user_language == strtolower($language) || (!$user_language && $system_language == strtolower($language))) {
                                            $language_status = "<span class='float-end checkbox-checked m0'></span>";
                                            $language_text = "<strong>" . $language . "</strong>";
                                        }

                                        if ($login_user->user_type == "staff") {
                                            echo ajax_anchor(get_uri("team_members/save_personal_language/$language"), $language_text . $language_status, array("class" => "dropdown-item clearfix", "data-reload-on-success" => "1"));
                                        } else {
                                            echo ajax_anchor(get_uri("clients/save_personal_language/$language"), $language_text . $language_status, array("class" => "dropdown-item clearfix", "data-reload-on-success" => "1"));
                                        }
                                    }
                                    ?>
                                </li>
                            </ul>
                        </li>

                    <?php } ?>

                    <li class="nav-item dropdown">
                        <?php echo js_anchor("<i data-feather='bell' class='icon'></i>", array("id" => "web-notification-icon", "class" => "nav-link dropdown-toggle", "data-bs-toggle" => "dropdown")); ?>
                        <div class="dropdown-menu dropdown-menu-end notification-dropdown w400">
                            <div class="dropdown-details card bg-white m0">
                                <div class="list-group">
                                    <span class="list-group-item inline-loader p10"></span>                          
                                </div>
                            </div>
                            <div class="card-footer text-center mt-2">
                                <?php echo anchor("notifications", app_lang('see_all')); ?>
                            </div>
                        </div>
                    </li>

                    <?php if (get_setting("module_message") && can_access_messages_module()) { ?>
                        <li class="nav-item dropdown hidden-sm <?php echo ($login_user->user_type === "client" && !get_setting("client_message_users")) ? "hide" : ""; ?>">
                            <?php echo js_anchor("<i data-feather='mail' class='icon'></i>", array("id" => "message-notification-icon", "class" => "nav-link dropdown-toggle", "data-bs-toggle" => "dropdown")); ?>
                            <div class="dropdown-menu dropdown-menu-end w300">
                                <div class="dropdown-details card bg-white m0">
                                    <div class="list-group">
                                        <span class="list-group-item inline-loader p10"></span>                          
                                    </div>
                                </div>
                                <div class="card-footer text-center">
                                    <?php echo anchor("messages", app_lang('see_all')); ?>
                                </div>
                            </div>
                        </li>
                    <?php } ?>

                    <li class="nav-item dropdown">
                        <a id="user-dropdown" href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                            <span class="avatar-xs avatar me-1" >
                                <img alt="..." src="<?php echo get_avatar($login_user->image); ?>">
                            </span>
                            <span class="user-name ml10"><?php echo $login_user->first_name . " " . $login_user->last_name; ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end w200">
                            <?php if ($login_user->user_type == "client") { ?>
                                <li><?php echo get_client_contact_profile_link($login_user->id . '/general', "<i data-feather='user' class='icon-16 me-2'></i>" . app_lang('my_profile'), array("class" => "dropdown-item")); ?></li>
                                <li><?php echo get_client_contact_profile_link($login_user->id . '/account', "<i data-feather='key' class='icon-16 me-2'></i>" . app_lang('change_password'), array("class" => "dropdown-item")); ?></li>
                                <li><?php echo get_client_contact_profile_link($login_user->id . '/my_preferences', "<i data-feather='settings' class='icon-16 me-2'></i>" . app_lang('my_preferences'), array("class" => "dropdown-item")); ?></li>
                            <?php } else { ?>
                                <li><?php echo get_team_member_profile_link($login_user->id . '/general', "<i data-feather='user' class='icon-16 me-2'></i>" . app_lang('my_profile'), array("class" => "dropdown-item")); ?></li>
                                <li><?php echo get_team_member_profile_link($login_user->id . '/account', "<i data-feather='key' class='icon-16 me-2'></i>" . app_lang('change_password'), array("class" => "dropdown-item")); ?></li>
                                <li><?php echo get_team_member_profile_link($login_user->id . '/my_preferences', "<i data-feather='settings' class='icon-16 me-2'></i>" . app_lang('my_preferences'), array("class" => "dropdown-item")); ?></li>
                            <?php } ?>

                            <?php if (get_setting("show_theme_color_changer") === "yes") { ?>

                                <li class="dropdown-divider"></li>    
                                <li class="pl10 ms-2 mt10 theme-changer">
                                    <?php echo get_custom_theme_color_list(); ?>
                                </li>

                            <?php } ?>

                            <li class="dropdown-divider"></li>
                            <li><a href="<?php echo_uri('signin/sign_out'); ?>" class="dropdown-item"><i data-feather="log-out" class='icon-16 me-2'></i> <?php echo app_lang('sign_out'); ?></a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div><!--/.nav-collapse -->
    </div>
</nav>

<script type="text/javascript">
    //close navbar collapse panel on clicking outside of the panel
    $(document).click(function (e) {
        if (!$(e.target).is('#navbar') && isMobile()) {
            $('#navbar').collapse('hide');
        }
    });

    var notificationOptions = {};

    $(document).ready(function () {
        //load message notifications
        var messageOptions = {},
                messageIcon = "#message-notification-icon",
                notificationIcon = "#web-notification-icon";

        //check message notifications
        messageOptions.notificationUrl = "<?php echo_uri('messages/get_notifications'); ?>";
        messageOptions.notificationStatusUpdateUrl = "<?php echo_uri('messages/update_notification_checking_status'); ?>";
        messageOptions.checkNotificationAfterEvery = "<?php echo get_setting('check_notification_after_every'); ?>";
        messageOptions.icon = "mail";
        messageOptions.notificationSelector = $(messageIcon);
        messageOptions.isMessageNotification = true;

        checkNotifications(messageOptions);

        window.updateLastMessageCheckingStatus = function () {
            checkNotifications(messageOptions, true);
        };

        $('body').on('show.bs.dropdown', messageIcon, function () {
            checkNotifications(messageOptions, true);
        });




        //check web notifications
        notificationOptions.notificationUrl = "<?php echo_uri('notifications/count_notifications'); ?>";
        notificationOptions.notificationStatusUpdateUrl = "<?php echo_uri('notifications/update_notification_checking_status'); ?>";
        notificationOptions.checkNotificationAfterEvery = "<?php echo get_setting('check_notification_after_every'); ?>";
        notificationOptions.icon = "bell";
        notificationOptions.notificationSelector = $(notificationIcon);
        notificationOptions.notificationType = "web";
        notificationOptions.pushNotification = "<?php echo get_setting("enable_push_notification") && $login_user->enable_web_notification && !get_setting('user_' . $login_user->id . '_disable_push_notification') ? true : false ?>";

        checkNotifications(notificationOptions); //start checking notification after starting the message checking 

        if (isMobile()) {
            //for mobile devices, load the notifications list with the page load
            notificationOptions.notificationUrlForMobile = "<?php echo_uri('notifications/get_notifications'); ?>";
            checkNotifications(notificationOptions);
        }

        $('body').on('show.bs.dropdown', notificationIcon, function () {
            notificationOptions.notificationUrl = "<?php echo_uri('notifications/get_notifications'); ?>";
            checkNotifications(notificationOptions, true);
        });

        $('[data-bs-toggle="tooltip"]').tooltip();
    });

</script>