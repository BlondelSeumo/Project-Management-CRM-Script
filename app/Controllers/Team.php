<?php

namespace App\Controllers;

class Team extends Security_Controller {

    function __construct() {
        parent::__construct();
        $this->access_only_admin();
    }

    function index() {
        return $this->template->rander("team/index");
    }

    /* load team add/edit modal */

    function modal_form() {
        $this->validate_submitted_data(array(
            "id" => "numeric"
        ));

        $team_members = $this->Users_model->get_all_where(array("deleted" => 0, "user_type" => "staff"))->getResult();
        $members_dropdown = array();

        foreach ($team_members as $team_member) {
            $members_dropdown[] = array("id" => $team_member->id, "text" => $team_member->first_name . " " . $team_member->last_name);
        }

        $view_data['members_dropdown'] = json_encode($members_dropdown);
        $view_data['model_info'] = $this->Team_model->get_one($this->request->getPost('id'));
        return $this->template->view('team/modal_form', $view_data);
    }

    /* add/edit a team */

    function save() {

        $this->validate_submitted_data(array(
            "id" => "numeric",
            "title" => "required",
            "members" => "required"
        ));

        $id = $this->request->getPost('id');
        $data = array(
            "title" => $this->request->getPost('title'),
            "members" => $this->request->getPost('members')
        );

        $save_id = $this->Team_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    /* delete/undo a team */

    function delete() {
        $this->validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->request->getPost('id');
        if ($this->request->getPost('undo')) {
            if ($this->Team_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => app_lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, app_lang('error_occurred')));
            }
        } else {
            if ($this->Team_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of team prepared for datatable */

    function list_data() {
        $list_data = $this->Team_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    /* reaturn a row of team list table */

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Team_model->get_details($options)->getRow();
        return $this->_make_row($data);
    }

    /* prepare a row of team list table */

    private function _make_row($data) {
        $total_members = "<span class='badge badge-light w100'><i data-feather='users' class='icon-16'></i> " . count(explode(",", $data->members)) . "</span>";
        return array($data->title,
            modal_anchor(get_uri("team/members_list"), $total_members, array("title" => app_lang('team_members'), "data-post-members" => $data->members)),
            modal_anchor(get_uri("team/modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_team'), "data-post-id" => $data->id))
            . js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete_team'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("team/delete"), "data-action" => "delete"))
        );
    }

    function members_list() {
        $view_data['team_members'] = $this->Users_model->get_team_members($this->request->getPost('members'))->getResult();
        return $this->template->view('team/members_list', $view_data);
    }

}

/* End of file team.php */
/* Location: ./app/controllers/team.php */