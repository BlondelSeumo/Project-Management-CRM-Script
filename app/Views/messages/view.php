<div class="message-container-<?php echo $message_info->id; ?>" data-total_messages="<?php echo $found_rows ?>">
    <?php
    if ($mode === "inbox") {
        if ($is_reply) {
            $user_image = $login_user->image;
        } else {
            $user_image = $message_info->user_image;
        }
    } if ($mode === "sent_items") {
        if ($is_reply) {
            $user_image = $message_info->user_image;
        } else {
            $user_image = $login_user->image;
        }
    }
    ?>

    <div class="b-b p15 m0 bg-white">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex">
                    <div class="flex-shrink-0 pe-2"> 
                        <span class="avatar avatar-sm">
                            <img src="<?php echo get_avatar($user_image); ?>" alt="..." />
                        </span>
                    </div>
                    <div class="w-100">
                        <div class="clearfix">
                            <?php
                            $message_user_id = $message_info->from_user_id;
                            if ($mode === "sent_items" && $is_reply != "1" || $mode === "inbox" && $is_reply == "1") {
                                $message_user_id = $message_info->to_user_id;
                                ?>
                                <label class="badge bg-success"><?php echo app_lang("to"); ?></label>
                            <?php } ?>
                            <?php
                            if ($message_info->user_type == "client") {
                                echo get_client_contact_profile_link($message_user_id, $message_info->user_name, array("class" => "dark strong"));
                            } else {
                                echo get_team_member_profile_link($message_user_id, $message_info->user_name, array("class" => "dark strong"));
                            }
                            ?>
                            <span class="text-off float-end"><?php echo format_to_relative_time($message_info->created_at); ?></span>

                            <span class="float-end dropdown">
                                <div class="text-off dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="true" >
                                    <i data-feather="chevron-down" class="icon"></i>
                                </div>
                                <ul class="dropdown-menu" role="menu">
                                    <li role="presentation"><?php echo ajax_anchor(get_uri("messages/delete_my_messages/$message_info->id"), "<i data-feather='x' class='icon-16'></i> " . app_lang('delete'), array("class" => "dropdown-item", "title" => app_lang('delete'), "data-fade-out-on-success" => ".message-container-$message_info->id")); ?> </li>
                                </ul>
                            </span>
                        </div>
                        <p class="pt10 pb10 b-b">
                            <?php echo app_lang("subject"); ?>:  <?php echo $message_info->subject; ?>  
                        </p>

                        <p>
                            <?php echo nl2br(link_it($message_info->message)); ?>
                        </p>

                        <div class="comment-image-box clearfix">
                            <?php
                            $files = unserialize($message_info->files);
                            $total_files = count($files);

                            if ($total_files) {
                                echo view("includes/timeline_preview", array("files" => $files));
                                $download_caption = app_lang('download');
                                if ($total_files > 1) {
                                    $download_caption = sprintf(app_lang('download_files'), $total_files);
                                }
                                echo "<i data-feather='paperclip' class='icon-16'></i>";
                                echo anchor(get_uri("messages/download_message_files/" . $message_info->id), $download_caption, array("class" => "float-end", "title" => $download_caption));
                            }
                            ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>



    <?php
    //if there are more then 5 messages, we'll show load more option.

    if ($found_rows > 5) {
        ?>    
        <div id="load-messages" class="b-b">
            <?php
            echo js_anchor(app_lang("load_more"), array("class" => "btn btn-default w-100 mt15 spinning-btn", "title" => app_lang("load_more"), "id" => "load-more-messages-link"));
            ?>
        </div>
        <div id="load-more-messages-container"></div>
        <?php
    }




    foreach ($replies as $reply_info) {
        ?>
        <?php echo view("messages/reply_row", array("reply_info" => $reply_info)); ?>
    <?php } ?>

    <div id="reply-form-container">
        <div id="reply-form-dropzone" class="post-dropzone">
            <?php echo form_open(get_uri("messages/reply"), array("id" => "message-reply-form", "class" => "general-form", "role" => "form")); ?>
            <div class="p15 box b-b">
                <div class="box-content avatar avatar-md pr15 d-table-cell">
                    <img src="<?php echo get_avatar($login_user->image); ?>" alt="..." />
                </div>
                <div class="box-content mb-3">
                    <input type="hidden" name="message_id" value="<?php echo $message_info->id; ?>">
                    <?php
                    echo form_textarea(array(
                        "id" => "reply_message",
                        "name" => "reply_message",
                        "class" => "form-control",
                        "placeholder" => app_lang('write_a_reply'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                        "data-rich-text-editor" => true,
                        "style" => "height: 6rem;"
                    ));
                    ?>
                    <?php echo view("includes/dropzone_preview"); ?>    
                    <footer class="card-footer b-a clearfix">
                        <button class="btn btn-default upload-file-button float-start me-auto btn-sm round" type="button" style="color:#7988a2"><i data-feather="camera" class='icon-16'></i> <?php echo app_lang("upload_file"); ?></button>
                        <button class="btn btn-primary float-end btn-sm " type="submit"><i data-feather="send" class='icon-16'></i> <?php echo app_lang("reply"); ?></button>
                    </footer>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            var uploadUrl = "<?php echo get_uri("messages/upload_file"); ?>";
            var validationUrl = "<?php echo get_uri("messages/validate_message_file"); ?>";

            var dropzone = attachDropzoneWithForm("#reply-form-dropzone", uploadUrl, validationUrl);

            $("#message-reply-form").appForm({
                isModal: false,
                onSuccess: function (result) {
                    $("#reply_message").val("");
                    $(result.data).insertBefore("#reply-form-container");
                    appAlert.success(result.message, {duration: 10000});
                    if (dropzone) {
                        dropzone.removeAllFiles();
                    }
                }
            });

            $("#load-more-messages-link").click(function () {
                loadMoreMessages();
            });

        });

        function loadMoreMessages(callback) {
            $("#load-more-messages-link").addClass("spinning");
            var $topMessageDiv = $(".js-message-reply").first();

            $.ajax({
                url: "<?php echo get_uri('messages/view_messages'); ?>",
                type: "POST",
                data: {
                    message_id: "<?php echo $message_info->id; ?>",
                    top_message_id: $topMessageDiv.attr("data-message_id")
                },
                success: function (response) {
                    if (response) {
                        $("#load-more-messages-container").prepend(response);
                        $("#load-more-messages-link").removeClass("spinning");

                        if (callback) {
                            callback(); //has more data?
                        }
                    } else {
                        $("#load-more-messages-link").remove(); //no more messages left
                    }

                }
            });
        }
    </script>
</div>