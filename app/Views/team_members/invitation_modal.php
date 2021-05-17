<?php echo form_open(get_uri("team_members/send_invitation"), array("id" => "invitation-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <br />
        <div class="form-group mb15">
            <label for="email" class=" col-md-12"><?php echo app_lang('invite_someone_to_join_as_a_team_member'); ?></label>
            <div class="col-md-12">
                <div class="send-invitation-field">
                    <div class="send-invitation-form clearfix pb10 ml10 mr10">
                        <div class="row">
                            <div class="col-md-11 p0">
                                <?php
                                echo form_input(array(
                                    "id" => "email",
                                    "name" => "email[]",
                                    "class" => "form-control",
                                    "placeholder" => app_lang('email'),
                                    "autofocus" => true,
                                    "data-rule-required" => true,
                                    "data-msg-required" => app_lang("field_required"),
                                    "data-rule-email" => true,
                                    "data-msg-required" => app_lang("enter_valid_email")
                                ));
                                ?>
                            </div>    
                            <div class="col-md-1">
                                <?php echo js_anchor("<i data-feather='x' class='icon-16'></i> ", array("class" => "remove-invitation delete ml10 mt-2")); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo js_anchor("<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_more'), array("class" => "add-invitation", "id" => "add-more-invitation")); ?>
            </div>
        </div>
        <br />
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="send" class="icon-16"></span> <?php echo app_lang('send'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#invitation-form").appForm({
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
            }
        });

        setTimeout(function () {
            $("#email").focus();
        }, 200);

        var $wrapper = $('.send-invitation-field'),
                $field = $('.send-invitation-form:first-child', $wrapper).clone(); //keep a clone for future use.

        $(".add-invitation", $(this)).click(function (e) {
            var $newField = $field.clone();

            var $newObj = $newField.appendTo($wrapper);
            $newObj.find("input").focus();

            $newObj.find('.remove-invitation').click(function () {
                $(this).closest('.send-invitation-form').remove();
            });

        });

        $(".remove-invitation").hide();

    });
</script>    