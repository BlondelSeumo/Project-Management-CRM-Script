<div id="page-content" class="page-wrapper clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "roles";
            echo view("settings/tabs", $tab_view);
            ?>
        </div>

        <div class="col-sm-9 col-lg-10">
            <div class="row">
                <div class="col-md-4">
                    <div id="role-list-box" class="card">
                        <div class="page-title clearfix">
                            <h4> <?php echo app_lang('roles'); ?></h4>
                            <div class="title-button-group">
                                <?php echo modal_anchor(get_uri("roles/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_role'), array("class" => "btn btn-default", "title" => app_lang('add_role'))); ?>
                            </div>
                        </div>
                        <div class="table-responsiv">
                            <table id="role-table" class="display clickable no-thead b-b-only" cellspacing="0" width="100%">            
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div id="role-details-section"> 
                        <div id="empty-role" class="text-center p15 box card " style="min-height: 150px;">
                            <div class="box-content" style="vertical-align: middle; height: 100%"> 
                                <div><?php echo app_lang("select_a_role"); ?></div>
                                <span data-feather="sliders" width="6rem" height="6rem" style="color:rgba(128, 128, 128, 0.1)"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#role-table").appTable({
            source: '<?php echo_uri("roles/list_data") ?>',
            columns: [
                {title: '<?php echo app_lang("name"); ?>'},
                {title: '', class: 'text-center option w125'}
            ],
            hideTools: true,
            onInitComplete: function () {
                var $role_list = $("#role-list-box"),
                        $empty_role = $("#empty-role");
                if ($empty_role.length && $role_list.length) {
                    $empty_role.height($role_list.height() - 30);
                }
            },
            displayLength: 1000
        });

        /*load a message details*/
        $("body").on("click", "tr", function () {
            //don't load this message if already has selected.
            if (!$(this).hasClass("active")) {
                var role_id = $(this).find(".role-row").attr("data-id");
                if (role_id) {
                    appLoader.show();
                    $("tr.active").removeClass("active");
                    $(this).addClass("active");
                    $.ajax({
                        url: "<?php echo get_uri("roles/permissions"); ?>/" + role_id,
                        success: function (result) {
                            appLoader.hide();
                            $("#role-details-section").html(result);
                        }
                    });
                }
            }
        });
    });
</script>