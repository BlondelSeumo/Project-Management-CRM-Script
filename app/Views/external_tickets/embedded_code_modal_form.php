<div class="general-form">
    <div class="modal-body clearfix">
        <div class="container-fluid">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        echo form_textarea(array(
                            "id" => "embedded-code",
                            "name" => "embedded-code",
                            "value" => $embedded,
                            "class" => "form-control",
                            "data-rich-text-editor" => false
                        ));
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
        <button type="button" id="copy-button" class="btn btn-primary"><span data-feather="copy" class="icon-16"></span> <?php echo app_lang('copy'); ?></button>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#copy-button").click(function () {
            var copyTextarea = document.querySelector('#embedded-code');
            copyTextarea.focus();
            copyTextarea.select();
            document.execCommand('copy');
        });
    });
</script>