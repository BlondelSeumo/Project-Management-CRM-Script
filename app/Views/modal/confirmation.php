<!-- Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModal" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 400px;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="confirmationModalTitle"><?php echo app_lang('delete') . "?"; ?></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="confirmationModalContent" class="modal-body">
                <div class="container-fluid">
                    <?php echo app_lang('delete_confirmation_message'); ?>
                </div>
            </div>
            <div class="modal-footer clearfix">
                <button id="confirmDeleteButton" type="button" class="btn btn-danger" data-bs-dismiss="modal"><i data-feather="trash-2" class="icon-16"></i> <?php echo app_lang("delete"); ?></button>
                <button type="button" class="btn btn-default" data-bs-dismiss="modal"><i data-feather="x" class="icon-16"></i> <?php echo app_lang('cancel'); ?></button>
            </div>
        </div>
    </div>
</div>