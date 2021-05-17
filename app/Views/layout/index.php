<?php
$dir = 'ltr';
if (get_setting("rtl")) {
    $dir = 'rtl';
}

helper('cookie');
$left_menu_minimized = get_cookie("left_menu_minimized");
?>
<!DOCTYPE html>
<html lang="en" dir="<?php echo $dir; ?>">
    <?php echo view('includes/head'); ?>
    <body class="<?php echo $left_menu_minimized ? "sidebar-toggled" : ""; ?>">

        <?php
        if ($topbar) {
            echo view($topbar);
        }

        if ($left_menu) {
            echo view('messages/chat/index.php');

            //show cartbox only in the store page
            if (uri_string() == "items/grid_view") {
                echo view('items/cart/index');
            }
        }
        ?>

        <div id="left-menu-toggle-mask">
            <?php
            if ($left_menu) {
                echo $left_menu;
            }

            $public_page_container = "";
            if (!$left_menu) {
                $public_page_container = "public-page-container";
            }
            ?>
            <div class="page-container overflow-auto <?php echo $public_page_container ?>">
                <div id="pre-loader">
                    <div id="pre-loade" class="app-loader"><div class="loading"></div></div>
                </div>
                <div class="scrollable-page main-scrollable-page">
                    <?php
                    if (isset($content_view) && $content_view != "") {
                        echo view($content_view);
                    }
                    ?>
                </div>
                <?php
                if ($topbar == "includes/public/topbar") {
                    echo view("includes/footer");
                }
                ?>

            </div>
        </div>

        <?php echo view('modal/index'); ?>
        <?php echo view('modal/confirmation'); ?>
        <?php echo view("includes/summernote"); ?>
        <div style='display: none;'>
            <script type='text/javascript'>
                feather.replace();

<?php
$session = \Config\Services::session();
$error_message = $session->getFlashdata("error_message");
$success_message = $session->getFlashdata("success_message");
if (isset($error)) {
    echo 'appAlert.error("' . $error . '");';
}
if (isset($error_message)) {
    echo 'appAlert.error("' . $error_message . '");';
}
if (isset($success_message)) {
    echo 'appAlert.success("' . $success_message . '", {duration: 10000});';
}
?>
            </script>
        </div>

    </body>
</html>