<?php
$textarea_style = "";
if ($custom_class == "h370") {
    $textarea_style = "height:326px";
}
?>

<div class="card bg-white <?php echo $custom_class; ?>">
    <div class="card-header">
        <i data-feather="book" class="icon-16"></i>&nbsp; <?php echo app_lang("sticky_note"); ?>
    </div>
    <div id="sticky-note-container">
        <?php
        echo form_textarea(array(
            "id" => "sticky-note",
            "name" => "note",
            "value" => $login_user->sticky_note ? $login_user->sticky_note : "",
            "class" => "sticky-note",
            "style" => $textarea_style
        ));
        ?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var $stickyNote = $("#sticky-note");

        var saveStickyNote = function () {
            $.ajax({
                url: "<?php echo get_uri("dashboard/save_sticky_note") ?>",
                data: {sticky_note: $stickyNote.val()},
                cache: false,
                type: 'POST'
            });
        };

        $stickyNote.change(function () {
            saveStickyNote();
        });

        //save sticky not on window refresh
        $stickyNote.keydown(function () {
            window.onbeforeunload = saveStickyNote;
        });

    });
</script>