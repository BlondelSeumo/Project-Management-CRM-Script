<div id="page-content" class="page-wrapper clearfix">

    <div class="view-container">
        <div class="card">
            <div class="page-title clearfix">
                <h4><?php echo app_lang("estimate_request_form_selection_title"); ?></h4>
            </div>

            <div class="card-body">
                <ul class="list-group mb0">
                    <?php
                    foreach ($estimate_forms as $form) {
                        echo "<li class='list-group-item'>" . anchor(get_uri("request_estimate/form/" . $form->id), $form->title) . "</li>";
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>