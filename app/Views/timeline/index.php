<div class="box">
    <div class="box-content">
        <div id="timeline-content" class="page-wrapper clearfix mb20">
            <?php echo view("timeline/post_form"); ?>
            <?php echo timeline_widget(array("limit" => 20, "offset" => 0, "is_first_load" => true)); ?>
        </div>
    </div>

    <?php if ($team_members) { ?>
        <div class="hidden-xs box-content bg-white" style="width: 250px; min-height: 100%;">
            <div id="user-list-container" >
                <div class="p15">
                    <?php
                    foreach ($team_members as $member) {
                        ?>
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0 me-2 mt-1">
                                <span class="avatar avatar-xs">
                                    <img src="<?php echo get_avatar($member->image); ?>" alt="..." />
                                </span>
                            </div>
                            <div class="w-100 clearfix">
                                <div class="float-start">
                                    <div><?php echo get_team_member_profile_link($member->id, $member->first_name . " " . $member->last_name, array("class" => "dark")); ?></div>
                                    <small class="text-off d-block"><?php echo $member->job_title; ?></small>
                                </div>
                                <div class="float-end">
                                    <?php
                                    if (get_setting("module_message")) {
                                        echo modal_anchor(get_uri("messages/modal_form/" . $member->id), "<i data-feather='mail' class='icon-16'></i>", array("class" => "btn btn-default btn-xs round send-message-btn", "title" => app_lang('send_message')));
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php }
                    ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<script type="text/javascript">
    $(document).ready(function () {


        setTimelineScrollable()
        $(window).resize(function () {
            setTimelineScrollable();
        });
    });


    setTimelineScrollable = function () {
        var options = {
            setHeight: $(window).height() - 85
        };

        initScrollbar('#user-list-container', options);

        //don't apply scrollbar for mobile devices
        if ($(window).width() <= 640) {
            $('#timeline-content').css({"overflow": "auto"});
        } else {
            options.callbacks = {
                onTotalScroll: function () {
                    if (!$(".load-more").hasClass("inline-loading")) {
                        $(".load-more").trigger("click"); //auto load more data
                    }
                }
            };
            initScrollbar('#timeline-content', options);
        }

    };
</script>
