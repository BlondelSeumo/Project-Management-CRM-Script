<?php echo form_open(get_uri("left_menus/save"), array("id" => "left-menu-settings-form", "class" => "general-form dashed-row", "role" => "form")); ?>

<input type="hidden" name="data" id="items-data" value=""/>
<input type="hidden" name="type" value="user"/>

<div class="card">
    <div class="page-title clearfix">
        <h4> <?php echo app_lang('left_menu'); ?></h4>
        <div class="title-button-group">
            <?php
            if (get_setting("user_" . $login_user->id . "_left_menu")) {
                echo anchor(get_uri("left_menus/restore/user"), "<span data-feather='refresh-cw' class='icon-16'></span> " . app_lang("restore_to_default"), array("class" => "btn btn-danger"));
            }
            ?>
            <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
        </div>
    </div>

    <div class="card-body">
        <?php echo view("left_menu/sortable_area"); ?>
    </div>
</div>

<?php echo form_close(); ?>

<?php echo view("left_menu/helper_js"); ?>

