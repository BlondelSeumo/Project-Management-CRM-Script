<div class="modal-body clearfix general-form bg-off-white pb0">
    <div class="container-fluid">
        <div class="col-md-12 clearfix">
            <?php echo $widget; ?>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
</div>

<script>
    $(document).ready(function () {
        initScrollbar('#project-timeline-container', {
            setHeight: 719
        });

        initScrollbar('#upcoming-event-container', {
            setHeight: 330
        });
    });
</script>