<?php echo form_open(get_uri("leads/save_lead_from_excel_file"), array("id" => "import-lead-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix import-lead-modal-body">
    <div class="container-fluid">
        <div id="upload-area">
            <?php
            echo view("includes/multi_file_uploader", array(
                "upload_url" => get_uri("leads/upload_excel_file"),
                "validation_url" => get_uri("leads/validate_import_leads_file"),
                "max_files" => 1,
                "hide_description" => true
            ));
            ?>
        </div>
        <input type="hidden" name="file_name" id="import_file_name" value="" />
        <div id="preview-area"></div>
    </div>
</div>

<div class="modal-footer">
    <?php echo anchor("leads/download_sample_excel_file", "<i data-feather='download' class='icon-16'></i> " . app_lang("download_sample_file"), array("title" => app_lang("download_sample_file"), "class" => "btn btn-default float-start")); ?>
    <button type="button" class="btn btn-default cancel-upload" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button id="form-previous" type="button" class="btn btn-default hide"><span data-feather="arrow-left-circle" class="icon-16"></span> <?php echo app_lang('back'); ?></button>
    <button id="form-next" type="button" disabled="true" class="btn btn-info text-white"><span data-feather="arrow-right-circle" class="icon-16"></span> <?php echo app_lang('next'); ?></button>
    <button id="form-submit" type="button" disabled="true" class="btn btn-primary start-upload hide"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('upload'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#import-lead-form").appForm({
            onSuccess: function () {
                location.reload();
            }
        });

        var $uploadArea = $("#upload-area"),
                $previewArea = $("#preview-area"),
                $previousButton = $("#form-previous"),
                $nextButton = $("#form-next"),
                $modalBody = $(".import-lead-modal-body"),
                $submitButton = $("#form-submit"),
                $ajaxModal = $("#ajaxModal");

        removeLargeModal(); //remove app-modal credentials on loading modal

        function addLargeModal() {
            $ajaxModal.addClass("import-client-app-modal");
            $ajaxModal.addClass("app-modal");
            $ajaxModal.find("div.modal-dialog").addClass("app-modal-body mw100p");
            $ajaxModal.find("div.modal-content").addClass("h100p");
        }

        function removeLargeModal() {
            $ajaxModal.find("div.modal-dialog").removeClass("app-modal-body mw100p");
            $ajaxModal.find("div.modal-content").removeClass("h100p");
            $ajaxModal.removeClass("app-modal");
        }

        $submitButton.click(function () {
            $("#import-lead-form").trigger("submit");
        });

        //validate the uploaded excel file by clicking next
        $nextButton.click(function () {
            var fileName = $("#uploaded-file-previews").find("input[type=hidden]:eq(1)").val();
            if (!fileName) {
                appAlert.error("<?php echo app_lang('something_went_wrong'); ?>");
                return false;
            }
            appLoader.show({container: ".import-lead-modal-body", css: "left:0;"});
            var $button = $(this);
            $button.attr("disabled", true);

            $("#import_file_name").val(fileName);


            $.ajax({
                url: "<?php echo get_uri('leads/validate_import_leads_file_data') ?>",
                type: 'POST',
                dataType: 'json',
                data: {file_name: fileName},
                success: function (result) {
                    appLoader.hide();
                    $button.removeAttr('disabled');

                    if (result.success) {
                        $uploadArea.addClass("hide");
                        $nextButton.addClass("hide");
                        $previousButton.removeClass("hide");
                        $submitButton.removeClass("hide");
                        $previewArea.removeClass("hide");

                        $previewArea.html(result.table_data);
                        $modalBody.height($(window).height() - 165);
                        $modalBody.addClass("overflow-y-scroll");
                        addLargeModal();

                        if (result.got_error) {
                            $submitButton.prop("disabled", true);
                        } else {
                            $submitButton.prop("disabled", false);
                        }
                    }
                }
            });


        });

        $previousButton.click(function () {
            $(this).addClass("hide");
            $submitButton.addClass("hide");
            $uploadArea.removeClass("hide");
            $previewArea.addClass("hide");
            $nextButton.removeClass("hide");

            $modalBody.height($(window).height() - 230);
            removeLargeModal();
        });
    });
</script>    
