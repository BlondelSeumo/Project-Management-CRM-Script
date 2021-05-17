<div class="modal-body clearfix">
    <div class="container-fluid">
        <p class="pb10"><?php echo app_lang("estimate_request_form_selection_title"); ?></p>
        <ul class="list-group mb0">
            <?php
            foreach ($estimate_forms as $form) {
                echo "<li class='list-group-item estimate_request_form'>" . anchor(get_uri("estimate_requests/submit_estimate_request_form/" . $form->id), $form->title) . "</li>";
            }
            ?>
        </ul>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
</div>


