<?php

echo ajax_anchor(get_uri("projects/add_remove_star/" . $project_id . "/add"), "<i data-feather='star' class='icon-16'></i>", array("data-real-target" => "#star-mark", "class" => "star-icon"));
