<!-- don't load the panel for 2nd time -->
<?php
if ($offset) {
    echo activity_logs_widget($activity_logs_params);
} else {
    ?>
    <div class="card">
        <div class="card-body">
            <?php echo activity_logs_widget($activity_logs_params); ?>
        </div>   
    </div> 
    <?php
}?>