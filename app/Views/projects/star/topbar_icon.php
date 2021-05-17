<li class="nav-item dropdown hidden-xs">
    <?php echo ajax_anchor(get_uri("projects/show_my_starred_projects/"), "<i data-feather='grid' class='icon'></i>", array("class" => "nav-link dropdown-toggle", "data-bs-toggle" => "dropdown", "data-real-target" => "#projects-quick-list-container")); ?>
    <div class="dropdown-menu dropdown-menu-start w400">
        <div id="projects-quick-list-container">
            <div class="list-group">
                <span class="list-group-item inline-loader p20"></span>                          
            </div>
        </div>
    </div>
</li>