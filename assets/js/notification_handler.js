checkNotifications = function (params, updateStatus) {
    if (params && params.notificationUrl) {
        var data = {check_notification: 1};
        if (params.isMessageNotification) {
            data = {active_message_id: getCookie("active_chat_id")};
        }

        $.ajax({
            url: params.notificationUrl,
            type: "POST",
            data: data,
            dataType: 'json',
            success: function (result) {
                if (result.success) {
                    if (result.total_notifications && result.total_notifications * 1) {

                        //for new message notification, we'll also change the color of the chat icon.
                        if (params.isMessageNotification && window.prepareUnreadMessageChatBox) {
                            window.prepareUnreadMessageChatBox(result.total_notifications);
                        }

                        params.notificationSelector.html("<i data-feather='" + params.icon + "' class='icon'></i> <span class='badge bg-danger up'>" + result.total_notifications + "</span>");
                        feather.replace();

                        //compaire if there are new notifications, if so, show the notification
                        if (params.showPushNotification && params.notificationSelector.attr("data-total") != result.total_notifications) {

                            //play a notification sound
                            var notificationSoundVolume = Number("0." + AppHelper.settings.notificationSoundVolume) || 0;
                            if (notificationSoundVolume) {
                                try {
                                    //load notification sound
                                    if ($("body").find("#notificationPlayer").attr("id") !== "notificationPlayer") {
                                        $("<audio></audio>").attr({
                                            'src': AppHelper.notificationSoundSrc,
                                            'id': 'notificationPlayer',
                                            'type': 'audio/mpeg'
                                        }).appendTo("body");
                                    }

                                    document.getElementById("notificationPlayer").volume = notificationSoundVolume;
                                    document.getElementById("notificationPlayer").play();
                                } catch (err) {
                                }
                            }
                        }

                        params.notificationSelector.attr("data-total", result.total_notifications);


                    }

                    params.notificationSelector.parent().find(".dropdown-details").html(result.notification_list);

                    //get notifications list for mobile devices on page load
                    if (!updateStatus && params.notificationUrlForMobile) {
                        $.ajax({
                            url: params.notificationUrlForMobile,
                            type: "POST",
                            data: data,
                            dataType: 'json',
                            success: function (customResultForMobile) {
                                if (customResultForMobile.success) {
                                    params.notificationSelector.parent().find(".dropdown-details").html(customResultForMobile.notification_list);
                                }
                            }
                        });
                    }

                    if (updateStatus) {
                        //update last notification checking time
                        $.ajax({
                            url: params.notificationStatusUpdateUrl,
                            success: function () {
                                params.notificationSelector.html("<i data-feather='" + params.icon + "' class='icon'></i>");
                                feather.replace();
                            }
                        });
                    }


                }

                if (!updateStatus) {
                    var push_notification = params.pushNotification;
                    if (!push_notification) {
                        push_notification = false;
                    }

                    if (!push_notification) {
                        //if the push notification is enabled, then wait for push notification
                        //if not, the check notification again after sometime
                        var check_notification_after_every = params.checkNotificationAfterEvery;
                        check_notification_after_every = check_notification_after_every * 1000;
                        if (check_notification_after_every < 10000) {
                            check_notification_after_every = 10000; //don't allow to call this requiest before 10 seconds
                        }

                        //overwrite the settings since we added the chat module.
                        //for chat, it should be 5000
                        if (params.isMessageNotification) {
                            check_notification_after_every = 5000;
                        }
                        
                        setTimeout(function () {
                            params.showPushNotification = true;
                            checkNotifications(params);
                        }, check_notification_after_every);
                    }
                }
            }
        });
    }
};

