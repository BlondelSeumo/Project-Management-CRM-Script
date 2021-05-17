<?php

namespace App\Controllers;

use App\Libraries\Left_menu;

/*
 * Types:
 * "" - Users default
 * "user" - Users custom & Clients custom
 * "client_default" - Clients default
 */

class Left_menus extends Security_Controller {

    private $left_menu;

    function __construct() {
        parent::__construct();
        $this->left_menu = new Left_menu();
    }

    private function check_left_menu_permission($type = "") {
        if ($type == "user") {
            if ($this->login_user->user_type == "staff") {
                $this->access_only_team_members();
            } else if ($this->login_user->user_type == "client") {
                $this->access_only_clients();
            }
        } else if (!$type || $type == "client_default") {
            $this->access_only_admin();
        }
    }

    function index($type = "") {
        $this->check_left_menu_permission($type);

        $view_data["available_items"] = $this->left_menu->get_available_items($type);
        $view_data["sortable_items"] = $this->left_menu->get_sortable_items($type);
        $view_data["preview"] = $this->left_menu->rander_left_menu(true, $type);

        if ($type == "user") {
            return $this->template->view("left_menu/user_left_menu", $view_data);
        } else {
            $view_data["setting_active_tab"] = ($type == "client_default") ? "client_left_menu" : "left_menu";
            $view_data["type"] = $type;

            return $this->template->rander("left_menu/index", $view_data);
        }
    }

    function save() {
        if (get_setting("disable_editing_left_menu_by_clients") && $this->login_user->user_type == "client") {
            app_redirect("forbidden");
        }

        $type = $this->request->getPost("type");
        $this->check_left_menu_permission($type);

        $items_data = $this->request->getPost("data");
        if ($items_data) {
            $items_data = json_decode($items_data, true);

            //check if the setting menu has been added, if not, add it to the bottom
            if ($this->login_user->is_admin && $type != "client_default" && array_search("settings", array_column($items_data, "name")) === false) {
                $items_data[] = array("name" => "settings");
            }

            $items_data = serialize($items_data);
        }

        if ($type == "user") {
            $this->Settings_model->save_setting("user_" . $this->login_user->id . "_left_menu", $items_data);
            echo json_encode(array("success" => true, 'redirect_to' => get_uri($this->_prepare_user_custom_redirect_to_url()), 'message' => app_lang('settings_updated')));
        } else {
            if ($type == "client_default") {
                $this->Settings_model->save_setting("default_client_left_menu", $items_data);
            } else {
                $this->Settings_model->save_setting("default_left_menu", $items_data);
            }

            echo json_encode(array("success" => true, 'message' => app_lang('settings_updated')));
        }
    }

    private function _prepare_user_custom_redirect_to_url() {
        $redirect_to = "team_members/view/" . $this->login_user->id . "/left_menu";
        if ($this->login_user->user_type == "client") {
            $redirect_to = "clients/contact_profile/" . $this->login_user->id . "/left_menu";
        }

        return $redirect_to;
    }

    function add_menu_item_modal_form() {
        $model_info = new \stdClass();
        $model_info->title = $this->request->getPost("title");
        $model_info->url = $this->request->getPost("url");
        $model_info->is_sub_menu = $this->request->getPost("is_sub_menu");
        $model_info->open_in_new_tab = $this->request->getPost("open_in_new_tab");
        $model_info->icon = $this->request->getPost("icon");

        $view_data["model_info"] = $model_info;

        return $this->template->view("left_menu/add_menu_item_modal_form", $view_data);
    }

    function prepare_custom_menu_item_data() {
        $title = $this->request->getPost("title");
        $url = $this->request->getPost("url");
        $is_sub_menu = $this->request->getPost("is_sub_menu");
        $open_in_new_tab = $this->request->getPost("open_in_new_tab");
        $icon = $this->request->getPost("icon");

        $item_array = array("name" => $title, "url" => $url, "is_sub_menu" => $is_sub_menu, "icon" => $icon, "open_in_new_tab" => $open_in_new_tab);
        $item_data = $this->left_menu->_get_item_data($item_array);

        if ($item_data) {
            echo json_encode(array("success" => true, "item_data" => $item_data));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    function restore($type = "") {
        $this->check_left_menu_permission($type);

        if ($type == "user") {
            $this->Settings_model->save_setting("user_" . $this->login_user->id . "_left_menu", "");
            app_redirect($this->_prepare_user_custom_redirect_to_url());
        } else {
            if ($type == "client_default") {
                $this->Settings_model->save_setting("default_client_left_menu", "");
                app_redirect("left_menus/index/client_default");
            } else {
                $this->Settings_model->save_setting("default_left_menu", "");
                app_redirect("left_menus");
            }
        }
    }

}

/* End of file Left_menu.php */
/* Location: ./app/controllers/Left_menu.php */