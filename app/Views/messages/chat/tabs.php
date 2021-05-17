<div class="rise-chat-header box">
    <!--    <div class="box-content chat-back">
            <i data-feather="chevron-left" class="icon-16"></i>
        </div>-->

    <div class="box-content">
        <ul class="chat-tab p0 m0 nav nav-tabs box"  data-bs-toggle="ajax-tab" role="tablist">

            <li class="box-content" id="chat-inbox-tab-button">
                <a role="presentation" href="#" data-bs-target="#chat-inbox-tab"><i data-feather="message-circle" class="icon-16"></i></a>
            </li>

            <?php if ($show_users_list) { ?>
                <li class="box-content" id="chat-users-tab-button">
                    <a role="presentation"  href="<?php echo_uri("messages/users_list/staff"); ?>" data-bs-target="#chat-users-tab"> <i data-feather="user" class="icon-18"></i></a>
                </li>
            <?php } ?>

            <?php if ($show_clients_list) { ?>
                <li class="box-content" id="chat-clients-tab-button">
                    <a role="presentation"  href="<?php echo_uri("messages/users_list/client"); ?>" data-bs-target="#chat-clients-tab"><i data-feather="briefcase" class="icon-16"></i></a>
                </li>
            <?php } ?>

        </ul>
    </div>

</div>


<div class="rise-chat-body long clearfix">
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade" id="chat-inbox-tab">
            <?php echo view("messages/chat/chat_list", array("messages" => $messages)); ?>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="chat-users-tab"></div>
        <div role="tabpanel" class="tab-pane fade" id="chat-clients-tab"></div>
    </div>
</div>