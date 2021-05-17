<div class="form-group">
    <div class="col-sm-12">
        <div id="file-upload-dropzone" class="dropzone mb15">

        </div>
        <div id="file-upload-dropzone-scrollbar">
            <div id="uploaded-file-previews">
                <div id="file-upload-row" class="box">
                    <div class="preview box-content pr15" style="width:100px;">
                        <img data-dz-thumbnail class="upload-thumbnail-sm" />
                        <div class="progress upload-progress-sm active mt5" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                            <div class="progress-bar progress-bar-striped progress-bar-animated progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                        </div>
                    </div>
                    <div class="box-content">
                        <p class="name" data-dz-name></p>
                        <p class="clearfix">
                            <span class="size float-start" data-dz-size></span>
                            <span data-dz-remove class="btn btn-default btn-sm border-circle float-end mr10 fs-14 margin-top-5">
                                <i data-feather="x" class="icon-16"></i>
                            </span>
                        </p>
                        <strong class="error text-danger" data-dz-errormessage></strong>
                        <input class="file-count-field" type="hidden" name="files[]" value="" />

                        <?php if (!isset($hide_description)) { ?>
                            <input class="form-control description-field" type="text" style="cursor: auto;" placeholder="<?php echo app_lang("description") ?>" />
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        fileSerial = 0;

        // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
        var previewNode = document.querySelector("#file-upload-row");
        previewNode.id = "";
        var previewTemplate = previewNode.parentNode.innerHTML;
        previewNode.parentNode.removeChild(previewNode);

        var dropzoneId = "#file-upload-dropzone";

        var disableButtonType = '[type="submit"]';
<?php if (isset($hide_description)) { ?>
            disableButtonType = '[type="button"]';
<?php } ?>

        var maxFiles = 1000;
<?php if (isset($max_files)) { ?>
            maxFiles = <?php echo $max_files; ?>;
<?php } ?>

        var projectFilesDropzone = new Dropzone(dropzoneId, {
            url: "<?php echo $upload_url; ?>",
            thumbnailWidth: 80,
            thumbnailHeight: 80,
            parallelUploads: 20,
            maxFilesize: 3000,
            previewTemplate: previewTemplate,
            dictDefaultMessage: '<?php echo app_lang("file_upload_instruction"); ?>',
            autoQueue: true,
            previewsContainer: "#uploaded-file-previews",
            clickable: true,
            maxFiles: maxFiles,
            sending: function (file, xhr, formData) {
                formData.append(AppHelper.csrfTokenName, AppHelper.csrfHash);
            },
            accept: function (file, done) {

                if (file.name.length > 200) {
                    done("Filename is too long.");
                    $(file.previewTemplate).find(".description-field").remove();
                }

                //validate the file?
                $.ajax({
                    url: "<?php echo $validation_url; ?>",
                    data: {file_name: file.name, file_size: file.size},
                    cache: false,
                    type: 'POST',
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            fileSerial++;
                            $(file.previewTemplate).find(".description-field").attr("name", "description_" + fileSerial);
                            $(file.previewTemplate).append('<input type="hidden" name="file_name_' + fileSerial + '" value="' + file.name + '" />\n\
                                <input type="hidden" name="file_size_' + fileSerial + '" value="' + file.size + '" />');
                            $(file.previewTemplate).find(".file-count-field").val(fileSerial);
                            done();
                        } else {
                            $(file.previewTemplate).find("input").remove();
                            done(response.message);
                        }
                    }
                });
            },
            processing: function () {
                $(dropzoneId).closest('form').find(disableButtonType).prop("disabled", true);

                this.on("maxfilesexceeded", function (file) {
                    this.removeAllFiles();
                    this.addFile(file);
                });
            },
            queuecomplete: function () {
                $(dropzoneId).closest('form').find(disableButtonType).prop("disabled", false);
            },
            fallback: function () {
                //add custom fallback;
                $("body").addClass("dropzone-disabled");
                $(dropzoneId).closest('form').find(disableButtonType).removeAttr('disabled');

                $("#file-upload-dropzone").hide();
                $(dropzoneId).closest('form').find(".modal-footer").prepend("<button id='add-more-file-button' type='button' class='btn  btn-default float-start'><i data-feather='plus-circle' class='icon-16'></i> " + "<?php echo app_lang("add_more"); ?>" + "</button>");

                $(dropzoneId).closest('form').find(".modal-footer").on("click", "#add-more-file-button", function () {
                    var descriptionDom = "<div class='mb5 pb5'><input class='form-control description-field'  name='description[]'  type='text' style='cursor: auto;' placeholder='<?php echo app_lang("description") ?>' /></div>";
<?php if (isset($hide_description)) { ?>
                        descriptionDom = "";
<?php } ?>

                    var newFileRow = "<div class='file-row pb10 pt10 b-b mb10'>"
                            + "<div class='pb10 clearfix '><button type='button' class='btn btn-xs btn-danger float-start mr10 remove-file'><i data-feather='x' class='icon-16'></i></button> <input class='float-start' type='file' name='manualFiles[]' /></div>"
                            + descriptionDom
                            + "</div>";
                    $("#uploaded-file-previews").prepend(newFileRow);
                });
                $("#add-more-file-button").trigger("click");
                $("#uploaded-file-previews").on("click", ".remove-file", function () {
                    $(this).closest(".file-row").remove();
                });
            },
            success: function (file) {
                setTimeout(function () {
                    $(file.previewElement).find(".progress-bar-striped").removeClass("progress-bar-striped progress-bar-animated");
                }, 1000);
            }
        });

        document.querySelector(".start-upload").onclick = function () {
            projectFilesDropzone.enqueueFiles(projectFilesDropzone.getFilesWithStatus(Dropzone.ADDED));
        };
        document.querySelector(".cancel-upload").onclick = function () {
            projectFilesDropzone.removeAllFiles(true);
        };
        initScrollbar("#file-upload-dropzone-scrollbar", {setHeight: 280});

    });



</script>  