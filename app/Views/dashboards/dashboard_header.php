<?php
if (!isset($dashboard_info)) {
    $dashboard_info = new stdClass();
}

$title = app_lang("dashboard");
$color = "#fff";
$selected_dashboard = "border-circle";
if ($dashboard_type == "custom") {
    $title = $dashboard_info->title;
    $color = $dashboard_info->color;
    $selected_dashboard = "";
}
?>

<div class="clearfix mb15 dashbaord-header-area">

    <div class="clearfix float-start">
        <span class="float-start p10 pl0">
            <span style="background-color: <?php echo $color; ?>" class="color-tag border-circle"></span>
        </span>
        <h4 class="float-start"><?php echo $title; ?></h4>
    </div>        

    <div class="float-end clearfix">
        <span class="float-end dropdown dashboard-dropdown ml10">
            <div class="dropdown-toggle clickable" type="button" data-bs-toggle="dropdown" aria-expanded="true" >
                <i data-feather="more-horizontal" class="icon-16"></i>
            </div>
            <ul class="dropdown-menu dropdown-menu-end mt-1" role="menu">
                <?php if ($dashboard_type == "default") { ?>
                    <li role="presentation"><?php echo modal_anchor(get_uri("dashboard/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_new_dashboard'), array("title" => app_lang('add_new_dashboard'), "class" => "dropdown-item")); ?> </li>
                <?php } else { ?>
                    <li role="presentation" class="hidden-xs"><?php echo anchor(get_uri("dashboard/edit_dashboard/" . $dashboard_info->id), "<i data-feather='columns' class='icon-16'></i> " . app_lang('edit_dashboard'), array("title" => app_lang('edit_dashboard'), "class" => "dropdown-item")); ?> </li>
                    <li role="presentation"><?php echo modal_anchor(get_uri("dashboard/modal_form/" . $dashboard_info->id), "<i data-feather='edit' class='icon-16'></i> " . app_lang('edit_title'), array("title" => app_lang('edit_title'), "id" => "dashboard-edit-title-button", "class" => "dropdown-item")); ?> </li>
                    <li role="presentation"><?php echo js_anchor("<i data-feather='x' class='icon-16'></i>" . app_lang('delete'), array('title' => app_lang('delete'), "class" => "delete dropdown-item", "data-id" => $dashboard_info->id, "data-action-url" => get_uri("dashboard/delete"), "data-action" => "delete-confirmation", "data-success-callback" => "onDashboardDeleteSuccess")); ?> </li>
                <?php } ?>
            </ul>
        </span>

        <span class="float-end" id="dashboards-color-tags">
            <?php
            echo anchor(get_uri("dashboard"), "<span class='clickable p10 mr5 inline-block'><span style='background-color: #fff' class='color-tag $selected_dashboard'  title='" . app_lang("default_dashboard") . "'></span></span>");

            if ($dashboards) {
                foreach ($dashboards as $dashboard) {
                    $selected_dashboard = "";

                    if ($dashboard_type == "custom") {
                        if ($dashboard_info->id == $dashboard->id) {
                            $selected_dashboard = "border-circle";
                        }
                    }

                    $color = $dashboard->color ? $dashboard->color : "#83c340";

                    echo anchor(get_uri("dashboard/view/" . $dashboard->id), "<span class='clickable p10 mr5 inline-block'><span style='background-color: $color' class='color-tag $selected_dashboard' title='$dashboard->title'></span></span>");
                }
            }
            ?>
        </span>

    </div>
</div>

<script>
    $(document).ready(function () {
        //modify design for mobile devices
        if (isMobile()) {
            var $dashboardTags = $("#dashboards-color-tags"),
                    $dashboardTagsClone = $dashboardTags.clone(),
                    $dashboardDropdown = $(".dashboard-dropdown .dropdown-menu");

            $dashboardTags.addClass("hide");
            $dashboardTagsClone.removeClass("float-end");
            $dashboardTagsClone.children("span").addClass("p5 text-center inline-block");

            $dashboardTagsClone.children("span").find("a").each(function () {
                $(this).children("span").removeClass("p10").addClass("p5");
            });

            var liDom = "<li id='color-tags-container-for-mobile' class='bg-off-white text-center'></li>"
            $dashboardDropdown.prepend(liDom);
            $("#color-tags-container-for-mobile").html($dashboardTagsClone);
        }
    });
</script>