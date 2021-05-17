<div id="page-content" class="page-wrapper clearfix">
    <div class="row">
        <div class="col-sm-3 col-lg-2">
            <?php
            $tab_view['active_tab'] = "email_templates";
            echo view("settings/tabs", $tab_view);
            ?>
        </div>
        <div class="col-sm-9 col-lg-10">
            <div class="row">
                <div class="col-md-3">
                    <div id="template-list-box" class="card bg-white">
                        <div class="page-title clearfix">
                            <h4> <?php echo app_lang('email_templates'); ?></h4>
                        </div>

                        <ul class="nav nav-tabs vertical settings p15 d-block" role="tablist">
                            <?php
                            foreach ($templates as $template => $value) {

                                //collapse the selected template tab panel
                                $collapse_in = "";
                                $collapsed_class = "collapsed";
                                ?>
                                <div class="clearfix settings-anchor <?php echo $collapsed_class; ?>" data-bs-toggle="collapse" data-bs-target="#settings-tab-<?php echo $template; ?>">
                                    <?php echo app_lang($template); ?>
                                </div>
                                <?php
                                echo "<div id='settings-tab-$template' class='collapse $collapse_in'>";
                                echo "<ul class='list-group help-catagory'>";

                                foreach ($value as $sub_template_name => $sub_template) {
                                    echo "<span class='email-template-row list-group-item clickable' data-name='$sub_template_name'>" . app_lang($sub_template_name) . "</span>";
                                }

                                echo "</ul>";
                                echo "</div>";
                            }
                            ?>
                        </ul>

                    </div>
                </div>
                <div class="col-md-9">
                    <div id="template-details-section"> 
                        <div id="empty-template" class="text-center p15 box card ">
                            <div class="box-content" style="vertical-align: middle; height: 100%"> 
                                <div><?php echo app_lang("select_a_template"); ?></div>
                                <span data-feather="code" width="15rem" height="15rem" style="color:#f6f8f8"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
load_css(array(
    "assets/js/summernote/summernote.css"
));
load_js(array(
    "assets/js/summernote/summernote.min.js"
));
?>


<script type="text/javascript">
    $(document).ready(function () {

        /*load a template details*/
        $(".email-template-row").click(function () {
            //don't load this message if already has selected.
            if (!$(this).hasClass("active")) {
                var template_name = $(this).attr("data-name");
                if (template_name) {
                    $(".email-template-row").removeClass("active")
                    $(this).addClass("active");
                    $.ajax({
                        url: "<?php echo get_uri("email_templates/form"); ?>/" + template_name,
                        success: function (result) {
                            $("#template-details-section").html(result);
                        }
                    });
                }
            }
        });
    });
</script>