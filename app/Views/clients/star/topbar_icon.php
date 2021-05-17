<?php
if ($login_user->user_type == "staff") {

    $access_client = get_array_value($login_user->permissions, "client");
    if ($login_user->is_admin || $access_client) {
        ?>
        <li class="nav-item dropdown hidden-xs">
            <?php echo ajax_anchor(get_uri("clients/show_my_starred_clients/"), "<i data-feather='briefcase' class='icon'></i>", array("class" => "nav-link dropdown-toggle", "data-bs-toggle" => "dropdown", "data-real-target" => "#clients-quick-list-container")); ?>
            <div class="dropdown-menu dropdown-menu-start w400">
                <div id="clients-quick-list-container">
                    <div class="list-group">
                        <span class="list-group-item inline-loader p20"></span>                          
                    </div>
                </div>
            </div>
        </li>

        <?php
    }
}