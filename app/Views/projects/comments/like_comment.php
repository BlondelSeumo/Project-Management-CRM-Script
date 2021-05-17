<?php
$total_likes = "";
if ($comment->total_likes) {
    $data_placement = ($comment->file_id || $comment->task_id) ? "data-placement='right'" : "";
    $total_likes = "<span $data_placement data-bs-toggle='tooltip' title='$comment->comment_likers'><i data-feather='thumbs-up' class='icon-14 text-warning icon-fill-warning'></i> $comment->total_likes</span>";
}

if ($comment->total_likes) {
    echo $total_likes;
}
?>

<script>
    $(document).ready(function () {
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>