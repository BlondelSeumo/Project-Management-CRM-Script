<div class="modal-body clearfix general-form">
    <div class="container-fluid">

        <?php
        if ($model_info->files) {
            $files = @unserialize($model_info->files);
            if (count($files)) {
                if (!$login_user->is_admin) {
                    ?>
                    <div class="col-md-12 mt15">
                        <?php
                        if ($files) {
                            $total_files = count($files);
                            echo view("includes/timeline_preview", array("files" => $files));
                        }
                        ?>
                    </div>
                    <?php
                }
            }
        }
        ?>

        <div class="clearfix">
            <div class="col-md-12">
                <strong class="font-18"><?php echo $model_info->title; ?></strong>
                <?php if ($model_info->show_in_client_portal && $login_user->is_admin && get_setting("module_order")) { ?>
                    <span class="ml5 text-off font-11" data-bs-toggle="tooltip" data-placement="right" title="<?php echo app_lang('showing_in_client_portal'); ?>"><i data-feather="shopping-cart" class="icon-16"></i></span>
                <?php } ?>
            </div>
        </div>

        <div class="col-md-12 mb15">
            <span class="badge item-rate-badge font-18 strong"><?php echo to_currency($model_info->rate); ?></span> <?php echo $model_info->unit_type ? "/" . $model_info->unit_type : ""; ?>
        </div>

        <div class="col-md-12 mb15">
            <?php echo $model_info->description ? nl2br(link_it($model_info->description)) : "-"; ?>
        </div>

        <?php
        if ($model_info->files) {
            $files = @unserialize($model_info->files);
            if (count($files)) {
                if ($login_user->is_admin && get_setting("module_order")) {
                    ?>
                    <div class="col-md-12 mt15">
                        <div class="mb15 text-off"><i data-feather="help-circle" class="icon-16"></i> <?php echo app_lang("item_image_sorting_help_message"); ?></div>
                        <div class="row">
                            <?php echo view("includes/sortable_file_list", array("files" => $model_info->files, "action_url" => get_uri("items/save_files_sort"), "id" => $model_info->id)); ?>
                        </div>
                    </div>
                    <?php
                }
            }
        }
        ?>

    </div>
</div>

<div class="modal-footer">
    <?php
    if ($login_user->user_type == "staff") {
        echo modal_anchor(get_uri("items/modal_form"), "<i data-feather='edit' class='icon-16'></i> " . app_lang('edit_item'), array("class" => "btn btn-default", "data-post-id" => $model_info->id, "title" => app_lang('edit_item')));
    }
    ?>
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <?php
    //show add to cart button on client portal
    if ($login_user->user_type == "client" && !$model_info->added_to_cart) {
        echo js_anchor("<i data-feather='shopping-cart' class='icon-16'></i> " . app_lang("add_to_cart"), array("class" => "btn btn-info text-white item-add-to-cart-btn", "data-item_id" => $model_info->id));
    }
    ?>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>