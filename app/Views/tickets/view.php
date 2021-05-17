<?php $user_id = $login_user->id; ?>
<div id="page-content" class="page-wrapper clearfix">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="page-title clearfix">
                    <h1><?php echo get_ticket_id($ticket_info->id) . " - " . $ticket_info->title ?></h1>
                    <div class="title-button-group p10">

                        <span class="dropdown inline-block">
                            <button class="btn btn-default dropdown-toggle caret mt0 mb0" type="button" data-bs-toggle="dropdown" aria-expanded="true">
                                <i data-feather='settings' class='icon-16'></i> <?php echo app_lang('actions'); ?>
                            </button>
                            <ul class="dropdown-menu float-end" role="menu">
                                <?php if ($login_user->user_type == "staff") { ?>
                                    <li role="presentation"><?php echo modal_anchor(get_uri("tickets/modal_form"), "<i data-feather='edit-2' class='icon-16'></i> " . app_lang('edit'), array("title" => app_lang('ticket'), "data-post-view" => "details", "data-post-id" => $ticket_info->id, "class" => "dropdown-item")); ?></li>
                                    <?php if ($can_create_tasks && !$ticket_info->task_id) { ?> 
                                        <li role="presentation"><?php echo modal_anchor(get_uri("projects/task_modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('create_new_task'), array("title" => app_lang('create_new_task'), "data-post-project_id" => $ticket_info->project_id, "data-post-ticket_id" => $ticket_info->id, "class" => "dropdown-item")); ?></li>
                                    <?php } ?>

                                <?php } ?>

                                <?php if ($ticket_info->status === "closed") { ?>
                                    <li role="presentation"><?php echo ajax_anchor(get_uri("tickets/save_ticket_status/$ticket_info->id/open"), "<i data-feather='check-circle' class='icon-16'></i> " . app_lang('mark_as_open'), array("class" => "dropdown-item", "title" => app_lang('mark_as_open'), "data-reload-on-success" => "1")); ?> </li>
                                <?php } else { ?>
                                    <li role="presentation"><?php echo ajax_anchor(get_uri("tickets/save_ticket_status/$ticket_info->id/closed"), "<i data-feather='check-circle' class='icon-16'></i> " . app_lang('mark_as_closed'), array("class" => "dropdown-item", "title" => app_lang('mark_as_closed'), "data-reload-on-success" => "1")); ?> </li>
                                <?php } ?>
                                <?php if ($ticket_info->assigned_to === "0" && $login_user->user_type == "staff") { ?>
                                    <li role="presentation"><?php echo ajax_anchor(get_uri("tickets/assign_to_me/$ticket_info->id"), "<i data-feather='user' class='icon-16'></i> " . app_lang('assign_to_me'), array("class" => "dropdown-item", "title" => app_lang('assign_myself_in_this_ticket'), "data-reload-on-success" => "1")); ?></li>
                                <?php } ?>
                                <?php if ($ticket_info->client_id === "0" && $login_user->user_type == "staff") { ?>
                                    <?php if ($can_create_client) { ?>
                                        <li role="presentation"><?php echo modal_anchor(get_uri("clients/modal_form"), "<i data-feather='plus' class='icon-16'></i> " . app_lang('link_to_new_client'), array("title" => app_lang('link_to_new_client'), "data-post-ticket_id" => $ticket_info->id, "class" => "dropdown-item")); ?></li>
                                    <?php } ?>
                                    <li role="presentation"><?php echo modal_anchor(get_uri("tickets/add_client_modal_form/$ticket_info->id"), "<i data-feather='link' class='icon-16'></i> " . app_lang('link_to_existing_client'), array("title" => app_lang('link_to_existing_client'), "class" => "dropdown-item")); ?></li>
                                <?php } ?>
                            </ul>
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div id="ticket-title-section">
                        <?php echo view("tickets/ticket_sub_title"); ?>
                    </div>

                    <?php
                    //for assending mode, show the comment box at the top
                    if (!$sort_as_decending) {
                        foreach ($comments as $comment) {
                            echo view("tickets/comment_row", array("comment" => $comment));
                        }
                    }
                    ?>

                    <div id="comment-form-container" >
                        <?php echo form_open(get_uri("tickets/save_comment"), array("id" => "comment-form", "class" => "general-form", "role" => "form")); ?>
                        <div class="p15 d-flex">
                            <div class="flex-shrink-0">
                                <div class="avatar avatar-md pr15">
                                    <img src="<?php echo get_avatar($login_user->image); ?>" alt="..." />
                                </div>
                            </div>

                            <div class="w-100">
                                <div id="ticket-comment-dropzone" class="post-dropzone form-group">
                                    <input type="hidden" name="ticket_id" value="<?php echo $ticket_info->id; ?>">
                                    <?php
                                    echo form_textarea(array(
                                        "id" => "description",
                                        "name" => "description",
                                        "class" => "form-control",
                                        "value" => get_setting('user_' . $user_id . '_signature'),
                                        "placeholder" => app_lang('write_a_comment'),
                                        "data-rule-required" => true,
                                        "data-msg-required" => app_lang("field_required"),
                                        "data-rich-text-editor" => true
                                    ));
                                    ?>
                                    <?php echo view("includes/dropzone_preview"); ?>
                                    <footer class="card-footer b-a clearfix ">
                                        <button class="btn btn-default upload-file-button float-start me-auto btn-sm round" type="button" style="color:#7988a2"><i data-feather='camera' class='icon-16'></i> <?php echo app_lang("upload_file"); ?></button>

                                        <?php
                                        if ($login_user->user_type === "staff") {
                                            echo modal_anchor(get_uri("tickets/insert_template_modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('insert_template'), array("class" => "btn btn-default float-start btn-sm round ml10", "title" => app_lang('insert_template'), "style" => "color: #7988a2", "data-post-ticket_type_id" => $ticket_info->ticket_type_id, "id" => "insert-template-btn"));
                                        }
                                        ?>

                                        <button class="btn btn-primary float-end btn-sm " type="submit"><i data-feather='send' class='icon-16'></i> <?php echo app_lang("post_comment"); ?></button>
                                    </footer>
                                </div>
                            </div>

                        </div>
                        <?php echo form_close(); ?>
                    </div>

                    <?php
                    //for decending mode, show the comment box at the bottom
                    if ($sort_as_decending) {
                        foreach ($comments as $comment) {
                            echo view("tickets/comment_row", array("comment" => $comment));
                        }
                    }
                    ?>


                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        var uploadUrl = "<?php echo get_uri("tickets/upload_file"); ?>";
        var validationUrl = "<?php echo get_uri("tickets/validate_ticket_file"); ?>";

        var decending = "<?php echo $sort_as_decending; ?>";

        var dropzone = attachDropzoneWithForm("#ticket-comment-dropzone", uploadUrl, validationUrl);

        $("#comment-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                $("#description").val("");

                if (decending) {
                    $(result.data).insertAfter("#comment-form-container");
                } else {
                    $(result.data).insertBefore("#comment-form-container");
                }

                appAlert.success(result.message, {duration: 10000});

                dropzone.removeAllFiles();
            }
        });

        if ("<?php echo!get_setting('user_' . $user_id . '_signature') == '' ?>") {
            $("#description").text("\n" + $("#description").text());
            $("#description").focus();
        }

        window.refreshAfterAddTask = true;

        var $inputField = $("#description"), $lastFocused;

        function saveCursorPositionOfRichEditor() {
            $inputField.summernote('saveRange');
            $lastFocused = "rich-editor";
        }

        //store the cursor position
        if (AppHelper.settings.enableRichTextEditor === "1") {
            $inputField.on("summernote.change", function (e) {
                saveCursorPositionOfRichEditor();
            });

            //it'll grab only cursor clicks
            $("body").on("click", ".note-editable", function () {
                saveCursorPositionOfRichEditor();
            });
        } else {
            $inputField.focus(function () {
                $lastFocused = document.activeElement;
            });
        }

        function insertTemplate(text) {
            if ($lastFocused === undefined) {
                return;
            }

            if (AppHelper.settings.enableRichTextEditor === "1") {
                $inputField.summernote('restoreRange');
                $inputField.summernote('focus');
                $inputField.summernote('pasteHTML', text);
            } else {
                var scrollPos = $lastFocused.scrollTop;
                var pos = 0;
                var browser = (($lastFocused.selectionStart || $lastFocused.selectionStart === "0") ? "ff" : (document.selection ? "ie" : false));

                if (browser === "ff") {
                    pos = $lastFocused.selectionStart;
                }

                var front = ($lastFocused.value).substring(0, pos);
                var back = ($lastFocused.value).substring(pos, $lastFocused.value.length);
                $lastFocused.value = front + text + back;
                pos = pos + text.length;

                $lastFocused.scrollTop = scrollPos;
            }

            //close the modal
            $("#close-template-modal-btn").trigger("click");
        }

        //init uninitialized rich editor to insert template 
        $("#insert-template-btn").click(function () {
            setSummernote($("#description"));
        });

        //insert ticket template
        $("body").on("click", "#ticket-template-table tr", function () {
            var template = $(this).find(".js-description").html();
            if (AppHelper.settings.enableRichTextEditor !== "1") {
                //insert only text when rich editor isn't enabled
                var template = $(this).find(".js-description").text();
            }

            if ($lastFocused === undefined) {
                if (AppHelper.settings.enableRichTextEditor === "1") {
                    $("#description").summernote("code", template);
                } else {
                    $("#description").text(template);
                }

                //close the modal
                $("#close-template-modal-btn").trigger("click");
            } else {
                insertTemplate(template);
            }

        });
    });
</script>
