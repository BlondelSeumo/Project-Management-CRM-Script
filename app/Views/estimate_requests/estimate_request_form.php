<?php $is_preview = (isset($is_preview) && $is_preview) ? 1 : 0; ?>

<style type="text/css">.post-file-previews {border:none !important; }</style>

<div id="estimate-form-preview" class="card  p15 no-border clearfix post-dropzone" style="max-width: 1000px; margin: auto;">
    <div class="card-body">

        <h3 id="estimate-form-title" class=" pl10 pr10"> <?php echo $model_info->title; ?></h3>

        <div class="pl10 pr10"><?php echo nl2br($model_info->description); ?></div>

        <?php if (isset($clients_dropdown) && $clients_dropdown) { ?>
            <div class="form-group mt15 mb15">
                <div class="pl10 pr10">
                    <label for="client_id" class=" col-md-12"><?php echo app_lang('client'); ?></label>
                    <div class="col-md-12">
                        <?php
                        echo form_dropdown("client_id", $clients_dropdown, array(), "class='select2 validate-hidden' id='client_id' data-rule-required='true', data-msg-required='" . app_lang('field_required') . "'");
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class=" pt10">
            <div class="table-responsive general-form ">
                <table id="estimate-form-table" class="display b-t no-thead b-b-only no-hover" cellspacing="0" width="100%">            
                </table>
            </div>

        </div>
        <?php if ($model_info->enable_attachment) { ?>
            <div class="clearfix pl10 pr10 b-b">
                <?php echo view("includes/dropzone_preview"); ?>    
            </div>
        <?php } ?>
        <div class="p15"> 
            <?php if ($model_info->enable_attachment) { ?>
                <button class="btn btn-default upload-file-button mr15 round" type="button" style="color:#7988a2"><i data-feather="camera" class='icon-16'></i> <?php echo app_lang("upload_file"); ?></button>
            <?php } ?>
            <button type="submit" class="btn btn-primary"><span data-feather="send" class="icon-16"></span> <?php echo app_lang('request_an_estimate'); ?></button>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        $("#estimate-form-table").appTable({
            source: '<?php echo_uri("estimate_requests/estimate_form_filed_list_data/" . $model_info->id) ?>',
            order: [[1, "asc"]],
            hideTools: true,
            displayLength: 100,
            columns: [
                {title: "<?php echo app_lang("title") ?>"},
                {visible: false},
                {visible: false}
            ],
            onInitComplete: function () {
                $(".dataTables_empty").hide();
            }
        });
        var enable_attachment = "<?php echo $model_info->enable_attachment; ?>";
        var isPreview = "<?php echo $is_preview; ?>";

        if (enable_attachment === "1" && isPreview === "0") {
            var uploadUrl = "<?php echo get_uri("estimate_requests/upload_file"); ?>";
            var validationUrl = "<?php echo get_uri("estimate_requests/validate_file"); ?>";
            var dropzone = attachDropzoneWithForm("#estimate-form-preview", uploadUrl, validationUrl);
        }

        $("#client_id").select2();
    });
</script>
