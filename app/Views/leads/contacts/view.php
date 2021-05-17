<?php echo view("includes/cropbox"); ?>
<div id="page-content" class="clearfix">
    <div class="bg-success p20">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <?php echo view("users/profile_image_section"); ?>
                </div>
                <div class="col-md-6">
                    <p> 
                        <?php
                        $client_link = anchor(get_uri("leads/view/" . $lead_info->id), $lead_info->company_name, array("class" => "white-link"));

                        if ($login_user->user_type === "client") {
                            $client_link = anchor(get_uri("leads/contact_profile/" . $login_user->id . "/company"), $lead_info->company_name, array("class" => "white-link"));
                        }

                        echo app_lang("company_name") . ": <b>" . $client_link . "</b>";
                        ?>

                    </p>
                    <?php if ($lead_info->address) { ?>
                        <p><?php echo nl2br($lead_info->address); ?>
                            <?php if ($lead_info->city) { ?>
                                <br /><?php echo $lead_info->city; ?>
                            <?php } ?>
                            <?php if ($lead_info->state) { ?>
                                <br /><?php echo $lead_info->state; ?>
                            <?php } ?>
                            <?php if ($lead_info->zip) { ?>
                                <br /><?php echo $lead_info->zip; ?>
                            <?php } ?>
                            <?php if ($lead_info->country) { ?>
                                <br /><?php echo $lead_info->country; ?>
                            <?php } ?>
                        </p>
                        <p>
                            <?php
                            if ($lead_info->website) {
                                $website = to_url($lead_info->website);
                                echo app_lang("website") . ": " . "<a target='_blank' href='" . $website . "' class='white-link'>$website</a>";
                                ?>
                            <?php } ?>
                            <?php if ($lead_info->vat_number) { ?>
                                <br /><?php echo app_lang("vat_number") . ": " . $lead_info->vat_number; ?>
                            <?php } ?>  
                        </p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>


    <ul data-bs-toggle="ajax-tab" class="nav nav-tabs no-border-top-radius" role="tablist">
        <li><a  role="presentation" href="<?php echo_uri("leads/contact_general_info_tab/" . $user_info->id); ?>" data-bs-target="#tab-general-info"> <?php echo app_lang('general_info'); ?></a></li>
        <li><a  role="presentation" href="<?php echo_uri("leads/company_info_tab/" . $user_info->client_id); ?>" data-bs-target="#tab-company-info"> <?php echo app_lang('company'); ?></a></li>
        <li><a  role="presentation" href="<?php echo_uri("leads/contact_social_links_tab/" . $user_info->id); ?>" data-bs-target="#tab-social-links"> <?php echo app_lang('social_links'); ?></a></li>
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade" id="tab-general-info"></div>
        <div role="tabpanel" class="tab-pane fade" id="tab-company-info"></div>
        <div role="tabpanel" class="tab-pane fade" id="tab-social-links"></div>

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

        var tab = "<?php echo $tab; ?>";
        if (tab === "general") {
            $("[data-bs-target='#tab-general-info']").trigger("click");
        } else if (tab === "company") {
            $("[data-bs-target='#tab-company-info']").trigger("click");
        } else if (tab === "social") {
            $("[data-bs-target='#tab-social-links']").trigger("click");
        } else if (tab === "my_preferences") {
            $("[data-bs-target='#tab-my-preferences']").trigger("click");
        }

    });
</script>