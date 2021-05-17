<div class="modal-body clearfix" id="insert-template-section">
    <div class="container-fluid">
        <div class="table-responsive">
            <table id="ticket-template-table" class="display no-thead no-padding clickable" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button id="close-template-modal-btn" type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#ticket-template-table").appTable({
            source: '<?php echo_uri("tickets/ticket_template_list_data/modal/" . $ticket_type_id) ?>',
            order: [[0, 'desc']],
            columns: [
                {title: '<?php echo app_lang("title"); ?>'}

            ]
        });

        $("#insert-template-section .toolbar-left-top").remove();
        var $customToolbar = $("#insert-template-section .custom-toolbar");
        $customToolbar.removeClass("col-md-10").addClass("col-md-12");
        $customToolbar.find(".dataTables_filter").addClass("float-none");
        $customToolbar.find("label").addClass("ticket-template-label");
        $customToolbar.find("input").addClass("ticket-template-search");
    });
</script>