<?php echo view("includes/cropbox"); ?>
<div id="page-content" class="clearfix">
    <div class="bg-dark-success clearfix">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="p20 row">
                        <?php echo view("users/profile_image_section"); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p20 row">
                        <p> 
                            <?php
                            $client_link = anchor(get_uri("clients/view/" . $client_info->id), $client_info->company_name, array("class" => "white-link"));

                            if ($login_user->user_type === "client") {
                                $client_link = anchor(get_uri("clients/contact_profile/" . $login_user->id . "/company"), $client_info->company_name, array("class" => "white-link"));
                            }

                            echo app_lang("company_name") . ": <b>" . $client_link . "</b>";
                            ?>

                        </p>
                        <?php if ($client_info->address) { ?>
                            <p><?php echo nl2br($client_info->address); ?>
                                <?php if ($client_info->city) { ?>
                                    <br /><?php echo $client_info->city; ?>
                                <?php } ?>
                                <?php if ($client_info->state) { ?>
                                    <br /><?php echo $client_info->state; ?>
                                <?php } ?>
                                <?php if ($client_info->zip) { ?>
                                    <br /><?php echo $client_info->zip; ?>
                                <?php } ?>
                                <?php if ($client_info->country) { ?>
                                    <br /><?php echo $client_info->country; ?>
                                <?php } ?>
                            </p>
                            <p>
                                <?php
                                if ($client_info->website) {
                                    $website = to_url($client_info->website);
                                    echo app_lang("website") . ": " . "<a target='_blank' href='" . $website . "' class='white-link'>$website</a>";
                                    ?>
                                <?php } ?>
                                <?php if ($client_info->vat_number) { ?>
                                    <br /><?php echo app_lang("vat_number") . ": " . $client_info->vat_number; ?>
                                <?php } ?>  
                            </p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <ul id="client-contact-tabs" data-bs-toggle="ajax-tab" class="nav nav-tabs scrollable-tabs b-b rounded-0" role="tablist">
        <li><a  role="presentation" href="<?php echo_uri("clients/contact_general_info_tab/" . $user_info->id); ?>" data-bs-target="#tab-general-info"> <?php echo app_lang('general_info'); ?></a></li>
        <li><a  role="presentation" href="<?php echo_uri("clients/company_info_tab/" . $user_info->client_id); ?>" data-bs-target="#tab-company-info"> <?php echo app_lang('company'); ?></a></li>
        <li><a  role="presentation" href="<?php echo_uri("clients/contact_social_links_tab/" . $user_info->id); ?>" data-bs-target="#tab-social-links"> <?php echo app_lang('social_links'); ?></a></li>
        <li><a role="presentation" href="<?php echo_uri("clients/account_settings/" . $user_info->id); ?>" data-bs-target="#tab-account-settings"> <?php echo app_lang('account_settings'); ?></a></li>
        <?php if ($user_info->id == $login_user->id) { ?>
            <li><a role="presentation" href="<?php echo_uri("clients/my_preferences/" . $user_info->id); ?>" data-bs-target="#tab-my-preferences"> <?php echo app_lang('my_preferences'); ?></a></li>
        <?php } ?>
        <?php if ($user_info->id == $login_user->id && !get_setting("disable_editing_left_menu_by_clients")) { ?>
            <li><a role="presentation" href="<?php echo_uri("left_menus/index/user"); ?>" data-bs-target="#tab-user-left-menu"> <?php echo app_lang('left_menu'); ?></a></li>
        <?php } ?>
        <?php if ($user_info->id == $login_user->id && get_setting("enable_gdpr") && (get_setting("clients_can_request_account_removal") || get_setting("allow_clients_to_export_their_data"))) { ?>
            <li><a role="presentation" href="<?php echo_uri("clients/gdpr/" . $user_info->id); ?>" data-bs-target="#tab-gdpr">GDPR</a></li>
        <?php } ?>
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade" id="tab-files"></div>
        <div role="tabpanel" class="tab-pane fade" id="tab-general-info"></div>
        <div role="tabpanel" class="tab-pane fade" id="tab-company-info"></div>
        <div role="tabpanel" class="tab-pane fade" id="tab-social-links"></div>
        <div role="tabpanel" class="tab-pane fade" id="tab-account-settings"></div>
        <div role="tabpanel" class="tab-pane fade" id="tab-my-preferences"></div>
        <div role="tabpanel" class="tab-pane fade" id="tab-user-left-menu"></div>
        <div role="tabpanel" class="tab-pane fade" id="tab-gdpr"></div>

    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $(".upload").change(function () {
            if (typeof FileReader == 'function' && !$(this).hasClass("hidden-input-file")) {
                showCropBox(this);
            } else {
                $("#profile-image-form").submit();
            }
        });
        $("#profile_image").change(function () {
            $("#profile-image-form").submit();
        });


        $("#profile-image-form").appForm({
            isModal: false,
            beforeAjaxSubmit: function (data) {
                $.each(data, function (index, obj) {
                    if (obj.name === "profile_image") {
                        var profile_image = replaceAll(":", "~", data[index]["value"]);
                        data[index]["value"] = profile_image;
                    }
                });
            },
            onSuccess: function (result) {
                if (typeof FileReader == 'function' && !result.reload_page) {
                    appAlert.success(result.message, {duration: 10000});
                } else {
                    location.reload();
                }
            }
        });

        setTimeout(function () {
            var tab = "<?php echo $tab; ?>";
            if (tab === "general") {
                $("[data-bs-target='#tab-general-info']").trigger("click");
            } else if (tab === "company") {
                $("[data-bs-target='#tab-company-info']").trigger("click");
            } else if (tab === "account") {
                $("[data-bs-target='#tab-account-settings']").trigger("click");
            } else if (tab === "social") {
                $("[data-bs-target='#tab-social-links']").trigger("click");
            } else if (tab === "my_preferences") {
                $("[data-bs-target='#tab-my-preferences']").trigger("click");
            } else if (tab === "left_menu") {
                $("[data-bs-target='#tab-user-left-menu']").trigger("click");
            }
        }, 210);

    });
</script>