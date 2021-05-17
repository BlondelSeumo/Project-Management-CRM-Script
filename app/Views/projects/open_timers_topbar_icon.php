<li class="nav-item dropdown hidden-xs">
    <?php echo ajax_anchor(get_uri("projects/show_my_open_timers/"), "<div class='animated-clock'></div><div class='animated-clock-sec'></div>", array("class" => "nav-link dropdown-toggle", "id" => "project-timer-icon", "data-bs-toggle" => "dropdown", "data-real-target" => "#my-open-timers-container")); ?>
    <div class="dropdown-menu dropdown-menu-start aside-xl m0 p0 w300">
        <div id="my-open-timers-container">
        </div>
    </div>
</li>