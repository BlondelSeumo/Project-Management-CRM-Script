<?php

namespace App\Libraries;

use App\Controllers\Security_Controller;

class Left_menu {

    private $ci = null;

    public function __construct() {
        $this->ci = new Security_Controller(false);
    }

    function get_available_items($type = "default") {
        $items_array = $this->_prepare_sidebar_menu_items($type);

        //remove used items
        $default_left_menu_items = $this->_get_left_menu_from_setting($type);

        foreach ($default_left_menu_items as $default_item) {
            unset($items_array[get_array_value($default_item, "name")]);
        }

        $items = "";
        foreach ($items_array as $item) {
            $items .= $this->_get_item_data($item, true);
        }

        return $items ? $items : "<span class='text-off empty-area-text'>" . app_lang('no_more_items_available') . "</span>";
    }

    private function _prepare_sidebar_menu_items($type = "") {
        $final_items_array = array();
        $items_array = $this->_get_sidebar_menu_items($type);

        foreach ($items_array as $item) {
            $main_menu_name = get_array_value($item, "name");

            if (isset($item["submenu"])) {
                //first add this menu removing the submenus
                if ($main_menu_name !== "finance" && $main_menu_name !== "help_and_support") {
                    $main_menu = $item;
                    unset($main_menu["submenu"]);
                    $final_items_array[$main_menu_name] = $main_menu;
                }

                $submenu = get_array_value($item, "submenu");
                foreach ($submenu as $key => $s_menu) {
                    //prepare help items differently
                    if ($main_menu_name == "help_and_support") {
                        $s_menu = $this->_make_customized_sub_menu_for_help_and_support($key, $s_menu);
                    }

                    if (get_array_value($s_menu, "class")) {
                        $final_items_array[get_array_value($s_menu, "name")] = $s_menu;
                    }
                }
            } else {
                $final_items_array[$main_menu_name] = $item;
            }
        }

        //add todo
        $final_items_array["todo"] = array("name" => "todo", "url" => "todo", "class" => "check-square");

        return $final_items_array;
    }

    private function _make_customized_sub_menu_for_help_and_support($key, $s_menu) {
        if ($key == 1) {
            $s_menu["name"] = "help_articles";
        } else if ($key == 2) {
            $s_menu["name"] = "help_categories";
        } else if ($key == 4) {
            $s_menu["name"] = "knowledge_base_articles";
        } else if ($key == 5) {
            $s_menu["name"] = "knowledge_base_categories";
        }

        return $s_menu;
    }

    private function _get_left_menu_from_setting_for_rander($is_preview = false, $type = "default") {
        $user_left_menu = get_setting("user_" . $this->ci->login_user->id . "_left_menu");
        $default_left_menu = ($type == "client_default" || $this->ci->login_user->user_type == "client") ? get_setting("default_client_left_menu") : get_setting("default_left_menu");
        $custom_left_menu = "";

        //for preview, show the edit type preview
        if ($is_preview) {
            $custom_left_menu = $default_left_menu; //default preview
            if ($type == "user") {
                $custom_left_menu = $user_left_menu ? $user_left_menu : $default_left_menu; //user level preview
            }
        } else {
            $custom_left_menu = $user_left_menu ? $user_left_menu : $default_left_menu; //page rander
        }

        return $custom_left_menu ? json_decode(json_encode(@unserialize($custom_left_menu)), true) : array();
    }

    private function _get_left_menu_from_setting($type) {
        if ($type == "client_default") {
            $default_left_menu = get_setting("default_client_left_menu");
        } else if ($type == "user") {
            $default_left_menu = get_setting("user_" . $this->ci->login_user->id . "_left_menu");
        } else {
            $default_left_menu = get_setting("default_left_menu");
        }

        return $default_left_menu ? json_decode(json_encode(@unserialize($default_left_menu)), true) : array();
    }

    public function _get_item_data($item, $is_default_item = false) {
        $name = get_array_value($item, "name");
        $url = get_array_value($item, "url");
        $is_sub_menu = get_array_value($item, "is_sub_menu");
        $open_in_new_tab = get_array_value($item, "open_in_new_tab");
        $icon = get_array_value($item, "icon");

        if ($name) {
            $sub_menu_class = "";
            if ($is_sub_menu) {
                $sub_menu_class = "ml20";
            }

            $extra_attr = "";
            $edit_button = "";
            $name_lang = "";
            if ($is_default_item || !$url) {
                $name_lang = app_lang($name);
            } else {
                //custom menu item
                $extra_attr = "data-url='$url' data-icon='$icon' data-custom_menu_item_id='" . rand(2000, 400000000) . "' data-open_in_new_tab='$open_in_new_tab'";
                $name_lang = $name;
                $edit_button = modal_anchor(get_uri("left_menus/add_menu_item_modal_form"), "<i data-feather='edit' class='icon-14'></i> ", array("title" => app_lang('edit'), "class" => "custom-menu-edit-button", "data-post-title" => $name, "data-post-url" => $url, "data-post-is_sub_menu" => $is_sub_menu, "data-post-icon" => $icon, "data-post-open_in_new_tab" => $open_in_new_tab));
            }

            return "<div data-value='" . $name . "' $extra_attr class='left-menu-item mb5 widget clearfix p10 bg-white $sub_menu_class'>
                        <span class='float-start text-start'><i data-feather='move' class='icon-16 text-off mr5'></i> " . $name_lang . "</span>
                        <span class='float-end invisible'>
                            <span class='clickable make-sub-menu' title='" . app_lang("make_previous_items_sub_menu") . "'><i data-feather='corner-right-down' class='icon-14'></i></span>
                            $edit_button
                            <span class='clickable delete-left-menu-item' title=" . app_lang("delete") . "><i data-feather='x' class='icon-14 text-danger'></i></span>
                        </span>
                    </div>";
        }
    }

    function get_sortable_items($type = "default") {
        $items = "<div id='menu-item-list-2' class='js-left-menu-scrollbar add-column-drop text-center p15 menu-item-list sortable-items-container'>";

        $default_left_menu_items = $this->_get_left_menu_from_setting($type);
        if (count($default_left_menu_items)) {
            foreach ($default_left_menu_items as $item) {
                $items .= $this->_get_item_data($item);
            }
        } else {
            $items .= "<span class='text-off empty-area-text'>" . app_lang('drag_and_drop_items_here') . "</span>";
        }

        $items .= "</div>";

        return $items;
    }

    function rander_left_menu($is_preview = false, $type = "default") {
        $final_left_menu_items = array();
        $custom_left_menu_items = $this->_get_left_menu_from_setting_for_rander($is_preview, $type);

        if ($custom_left_menu_items) {
            $left_menu_items = $this->_prepare_sidebar_menu_items($type);

            $last_menu_item = ""; //store last menu item to the get the data on creating submenu
            $last_final_menu_item = ""; //store the last menu item of final left menu to add submenu to this item 
            $parent_item_added_as_submenu = false;

            foreach ($custom_left_menu_items as $key => $value) {
                $item_value_array = $this->_get_item_array_value($value, $left_menu_items);

                $is_sub_menu = get_array_value($value, "is_sub_menu");
                if ($is_sub_menu) {
                    //this is a sub menu, move it to it's parent item                        
                    //since the parent item is also a standalone menu item, make a submenu of that too
                    //but if any other menu item which haven't any submenu, added as a submenu of a menu item which have submenu, that won't be added

                    $parent_item_array = $this->_get_item_array_value(get_array_value($custom_left_menu_items, $last_menu_item), $left_menu_items);
                    if (!$parent_item_added_as_submenu && !isset($parent_item_array["submenu"])) {
                        $final_left_menu_items[$last_final_menu_item]["submenu"][] = $parent_item_array;
                        $parent_item_added_as_submenu = true;
                    }

                    //add this item
                    array_push($final_left_menu_items[$last_final_menu_item]["submenu"], $item_value_array);
                } else {
                    $final_left_menu_items[] = $item_value_array;
                    $last_menu_item = $key;
                    $last_final_menu_item = end($final_left_menu_items);
                    $last_final_menu_item = key($final_left_menu_items);
                    $parent_item_added_as_submenu = false;
                }
            }
        }

        if (count($final_left_menu_items)) {
            $view_data["sidebar_menu"] = $final_left_menu_items;
        } else {
            $view_data["sidebar_menu"] = $this->_get_sidebar_menu_items($type);
        }

        $view_data["is_preview"] = $is_preview;
        $view_data["login_user"] = $this->ci->login_user;
        return view("includes/left_menu", $view_data);
    }

    private function _get_item_array_value($data_array, $left_menu_items) {
        $name = get_array_value($data_array, "name");
        $url = get_array_value($data_array, "url");
        $icon = get_array_value($data_array, "icon");
        $open_in_new_tab = get_array_value($data_array, "open_in_new_tab");
        $item_value_array = array();

        if ($url) { //custom menu item
            $item_value_array = array("name" => $name, "url" => $url, "is_custom_menu_item" => true, "class" => "$icon", "open_in_new_tab" => $open_in_new_tab);
        } else if (array_key_exists($name, $left_menu_items)) { //default menu items
            $item_value_array = get_array_value($left_menu_items, $name);
        }

        return $item_value_array;
    }

    private function _get_sidebar_menu_items($type = "") {
        $dashboard_menu = array("name" => "dashboard", "url" => "dashboard", "class" => "monitor");

        $selected_dashboard_id = get_setting("user_" . $this->ci->login_user->id . "_dashboard");
        if ($selected_dashboard_id) {
            $dashboard_menu = array("name" => "dashboard", "url" => "dashboard/view/" . $selected_dashboard_id, "class" => "monitor", "custom_class" => "dashboard-menu");
        }

        if ($this->ci->login_user->user_type == "staff" && $type !== "client_default") {

            $sidebar_menu = array("dashboard" => $dashboard_menu);

            $permissions = $this->ci->login_user->permissions;

            $access_expense = get_array_value($permissions, "expense");
            $access_invoice = get_array_value($permissions, "invoice");
            $access_ticket = get_array_value($permissions, "ticket");
            $access_client = get_array_value($permissions, "client");
            $access_lead = get_array_value($permissions, "lead");
            $access_timecard = get_array_value($permissions, "attendance");
            $access_leave = get_array_value($permissions, "leave");
            $access_estimate = get_array_value($permissions, "estimate");
            $access_order = get_array_value($permissions, "order");
            $access_items = ($this->ci->login_user->is_admin || $access_invoice || $access_estimate);

            $client_message_users = get_setting("client_message_users");
            $client_message_users_array = explode(",", $client_message_users);
            $access_messages = ($this->ci->login_user->is_admin || get_array_value($permissions, "message_permission") !== "no" || in_array($this->ci->login_user->id, $client_message_users_array));

            $manage_help_and_knowledge_base = ($this->ci->login_user->is_admin || get_array_value($permissions, "help_and_knowledge_base"));


            if (get_setting("module_timeline") == "1") {
                $sidebar_menu["timeline"] = array("name" => "timeline", "url" => "timeline", "class" => "send");
            }

            if (get_setting("module_event") == "1") {
                $sidebar_menu["events"] = array("name" => "events", "url" => "events", "class" => "calendar");
            }

            if (get_setting("module_note") == "1") {
                $sidebar_menu["notes"] = array("name" => "notes", "url" => "notes", "class" => "book");
            }

            if (get_setting("module_message") == "1" && $access_messages) {
                $sidebar_menu["messages"] = array("name" => "messages", "url" => "messages", "class" => "message-circle", "badge" => count_unread_message(), "badge_class" => "bg-primary");
            }


            if ($this->ci->login_user->is_admin || $access_client) {
                $sidebar_menu["clients"] = array("name" => "clients", "url" => "clients", "class" => "briefcase");
            }

            if (get_setting("module_lead") == "1" && ($this->ci->login_user->is_admin || $access_lead)) {
                $sidebar_menu["leads"] = array("name" => "leads", "url" => "leads", "class" => "layers");
            }

            $project_submenu = array(
                array("name" => "all_projects", "url" => "projects/all_projects"),
                array("name" => "tasks", "url" => "projects/all_tasks", "class" => "list")
            );

            if (get_setting("module_gantt")) {
                $project_submenu[] = array("name" => "gantt", "url" => "projects/all_gantt", "class" => "activity");
            }


            if (get_setting("module_project_timesheet")) {
                $project_submenu[] = array("name" => "timesheets", "url" => "projects/all_timesheets", "class" => "clock");
            }

            $sidebar_menu["projects"] = array("name" => "projects", "url" => "projects", "class" => "grid",
                "submenu" => $project_submenu
            );

            if (get_setting("module_estimate") && get_setting("module_estimate_request") && ($this->ci->login_user->is_admin || $access_estimate)) {

                $sidebar_menu["estimates"] = array("name" => "estimates", "url" => "estimates", "class" => "file",
                    "submenu" => array(
                        array("name" => "estimate_list", "url" => "estimates"),
                        array("name" => "estimate_requests", "url" => "estimate_requests", "class" => "file-text"),
                        array("name" => "estimate_forms", "url" => "estimate_requests/estimate_forms", "class" => "file")
                    )
                );
            } else if (get_setting("module_estimate") && ($this->ci->login_user->is_admin || $access_estimate)) {
                $sidebar_menu["estimates"] = array("name" => "estimates", "url" => "estimates", "class" => "file");
            }

            if (get_setting("module_invoice") == "1" && ($this->ci->login_user->is_admin || $access_invoice)) {
                $sidebar_menu["invoices"] = array("name" => "invoices", "url" => "invoices", "class" => "file-text");
            }

            if ($access_items && (get_setting("module_invoice") == "1" || get_setting("module_estimate") == "1" )) {
                $sidebar_menu["items"] = array("name" => "items", "url" => "items", "class" => "list");
            }

            if (get_setting("module_order") == "1" && ($this->ci->login_user->is_admin || $access_order)) {
                $order_submenu = array();
                $order_submenu[] = array("name" => "orders_list", "url" => "orders");
                $order_submenu[] = array("name" => "sales", "url" => "items/grid_view", "class" => "list");

                $sidebar_menu["orders"] = array("name" => "orders", "url" => "orders", "class" => "shopping-cart", "submenu" => $order_submenu);
            }

            if ((get_setting("module_invoice") == "1" || get_setting("module_expense") == "1") && ($this->ci->login_user->is_admin || $access_expense || $access_invoice)) {
                $finance_submenu = array();
                $finance_url = "";
                $show_payments_menu = false;
                $show_expenses_menu = false;


                if (get_setting("module_invoice") == "1" && ($this->ci->login_user->is_admin || $access_invoice)) {
                    $finance_submenu[] = array("name" => "invoice_payments", "url" => "invoice_payments", "class" => "dollar-sign");
                    $finance_url = "invoice_payments";
                    $show_payments_menu = true;
                }
                if (get_setting("module_expense") == "1" && ($this->ci->login_user->is_admin || $access_expense)) {
                    $finance_submenu[] = array("name" => "expenses", "url" => "expenses", "class" => "dollar-sign");
                    $finance_url = "expenses";
                    $show_expenses_menu = true;
                }

                if ($show_expenses_menu && $show_payments_menu) {
                    $finance_submenu[] = array("name" => "income_vs_expenses", "url" => "expenses/income_vs_expenses", "class" => "trending-up");
                }

                $sidebar_menu["finance"] = array("name" => "finance", "url" => $finance_url, "class" => "dollar-sign", "submenu" => $finance_submenu);
            }

            if (get_setting("module_ticket") == "1" && ($this->ci->login_user->is_admin || $access_ticket)) {

                $ticket_badge = 0;
                if ($this->ci->login_user->is_admin || $access_ticket === "all") {
                    $ticket_badge = count_new_tickets();
                } else if ($access_ticket === "specific") {
                    $specific_ticket_permission = get_array_value($permissions, "ticket_specific");
                    $ticket_badge = count_new_tickets($specific_ticket_permission);
                } else if ($access_ticket === "assigned_only") {
                    $ticket_badge = count_new_tickets("", $this->ci->login_user->id);
                }

                // 

                $sidebar_menu["tickets"] = array("name" => "tickets", "url" => "tickets", "class" => "life-buoy", "badge" => $ticket_badge, "badge_class" => "bg-primary");
            }


            if (get_array_value($this->ci->login_user->permissions, "hide_team_members_list") != "1") {
                $sidebar_menu["team_members"] = array("name" => "team_members", "url" => "team_members", "class" => "users");
            }


            if (get_setting("module_attendance") == "1" && ($this->ci->login_user->is_admin || $access_timecard)) {
                $sidebar_menu["attendance"] = array("name" => "attendance", "url" => "attendance", "class" => "clock");
            } else if (get_setting("module_attendance") == "1") {
                $sidebar_menu["attendance"] = array("name" => "attendance", "url" => "attendance/attendance_info", "class" => "clock");
            }

            if (get_setting("module_leave") == "1" && ($this->ci->login_user->is_admin || $access_leave)) {
                $sidebar_menu["leaves"] = array("name" => "leaves", "url" => "leaves", "class" => "log-out");
            } else if (get_setting("module_leave") == "1") {
                $sidebar_menu["leaves"] = array("name" => "leaves", "url" => "leaves/leave_info", "class" => "log-out");
            }

            if (get_setting("module_announcement") == "1") {
                $sidebar_menu["announcements"] = array("name" => "announcements", "url" => "announcements", "class" => "bell");
            }


            $module_help = get_setting("module_help") == "1" ? true : false;
            $module_knowledge_base = get_setting("module_knowledge_base") == "1" ? true : false;

            //prepere the help and suppor menues
            if ($module_help || $module_knowledge_base) {

                $help_knowledge_base_menues = array();
                $main_url = "help";

                if ($module_help) {
                    $help_knowledge_base_menues[] = array("name" => "help", "url" => $main_url, "class" => "help-circle");
                }

                //push the help manage menu if user has access
                if ($manage_help_and_knowledge_base && $module_help) {
                    $help_knowledge_base_menues[] = array("name" => "articles", "url" => "help/help_articles", "class" => "help-circle");
                    $help_knowledge_base_menues[] = array("name" => "categories", "url" => "help/help_categories", "class" => "help-circle");
                }

                if ($module_knowledge_base) {
                    $help_knowledge_base_menues[] = array("name" => "knowledge_base", "url" => "knowledge_base", "class" => "help-circle");
                }

                //push the knowledge_base manage menu if user has access
                if ($manage_help_and_knowledge_base && $module_knowledge_base) {
                    $help_knowledge_base_menues[] = array("name" => "articles", "category" => "help", "url" => "help/knowledge_base_articles", "class" => "help-circle");
                    $help_knowledge_base_menues[] = array("name" => "categories", "category" => "help", "url" => "help/knowledge_base_categories", "class" => "help-circle");
                }


                if (!$module_help) {
                    $main_url = "knowledge_base";
                }

                $sidebar_menu["help_and_support"] = array("name" => "help_and_support", "url" => $main_url, "class" => "help-circle",
                    "submenu" => $help_knowledge_base_menues
                );
            }



            if ($this->ci->login_user->is_admin) {
                $sidebar_menu["settings"] = array("name" => "settings", "url" => "settings/general", "class" => "settings");
            }
        } else {
            //client menu
            //get the array of hidden menu
            $hidden_menu = explode(",", get_setting("hidden_client_menus"));

            $sidebar_menu[] = $dashboard_menu;

            if (get_setting("module_event") == "1" && !in_array("events", $hidden_menu)) {
                $sidebar_menu[] = array("name" => "events", "url" => "events", "class" => "calendar");
            }

            //check message access settings for clients
            if (get_setting("module_message") && get_setting("client_message_users")) {
                $sidebar_menu[] = array("name" => "messages", "url" => "messages", "class" => "message-circle", "badge" => count_unread_message());
            }

            if (!in_array("projects", $hidden_menu)) {
                $sidebar_menu[] = array("name" => "projects", "url" => "projects/all_projects", "class" => "grid");
            }


            if (get_setting("module_estimate") && !in_array("estimates", $hidden_menu)) {
                $sidebar_menu[] = array("name" => "estimates", "url" => "estimates", "class" => "file");
            }

            if (get_setting("module_invoice") == "1") {
                if (!in_array("invoices", $hidden_menu)) {
                    $sidebar_menu[] = array("name" => "invoices", "url" => "invoices", "class" => "file-text");
                }
                if (!in_array("payments", $hidden_menu)) {
                    $sidebar_menu[] = array("name" => "invoice_payments", "url" => "invoice_payments", "class" => "dollar-sign");
                }
            }

            if (!in_array("store", $hidden_menu) && get_setting("client_can_access_store")) {
                $sidebar_menu[] = array("name" => "store", "url" => "items/grid_view", "class" => "truck");
                $sidebar_menu[] = array("name" => "orders", "url" => "orders", "class" => "shopping-cart");
            }

            if (get_setting("module_ticket") == "1" && !in_array("tickets", $hidden_menu)) {
                $sidebar_menu[] = array("name" => "tickets", "url" => "tickets", "class" => "life-buoy");
            }

            if (get_setting("module_announcement") == "1" && !in_array("announcements", $hidden_menu)) {
                $sidebar_menu[] = array("name" => "announcements", "url" => "announcements", "class" => "bell");
            }

            $sidebar_menu[] = array("name" => "users", "url" => "clients/users", "class" => "users");

            if (get_setting("client_can_view_files")) {
                $sidebar_menu[] = array("name" => "files", "url" => "clients/files/" . $this->ci->login_user->id . "/page_view", "class" => "image");
            }

            $sidebar_menu[] = array("name" => "my_profile", "url" => "clients/contact_profile/" . $this->ci->login_user->id, "class" => "settings");

            if (get_setting("module_knowledge_base") == "1" && !in_array("knowledge_base", $hidden_menu)) {
                $sidebar_menu[] = array("name" => "knowledge_base", "url" => "knowledge_base", "class" => "help-circle");
            }
        }

        return $sidebar_menu;
    }

}
