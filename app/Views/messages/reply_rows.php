<?php

foreach ($replies as $reply_info) {
    echo view("messages/reply_row", array("reply_info" => $reply_info));
} 