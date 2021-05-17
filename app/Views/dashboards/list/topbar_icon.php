<li class="nav-item dropdown hidden-xs">
    <?php echo ajax_anchor(get_uri("dashboard/show_my_dashboards/"), "<i data-feather='monitor' class='icon'></i>", array("class" => "nav-link dropdown-toggle", "data-bs-toggle" => "dropdown", "data-real-target" => "#my-dashboards-list-container")); ?>
    <div class="dropdown-menu dropdown-menu-start w300">
        <div id="my-dashboards-list-container">
            <div class="list-group">
                <span class="list-group-item inline-loader p20"></span>                          
            </div>
        </div>
    </div>
</li>