<?php

if (get_setting("csrf_protection")) {
    ?>
    <script>

        var data = {};
        data[AppHelper.csrfTokenName] = AppHelper.csrfHash;
        $.ajaxSetup({
            data: data
        });
    </script>
    <?php

}