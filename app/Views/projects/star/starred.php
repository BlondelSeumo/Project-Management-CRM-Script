<?php

echo ajax_anchor(get_uri("projects/add_remove_star/" . $project_id . "/remove"), "<i data-feather='star' class='icon-16 icon-fill-warning'></i>", array("data-real-target" => "#star-mark", "class" => "star-icon"));
