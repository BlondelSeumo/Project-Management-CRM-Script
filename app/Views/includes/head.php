<head>
    <?php echo view('includes/meta'); ?>
    <?php echo view('includes/helper_js'); ?>
    <?php echo view('includes/plugin_language_js'); ?>

    <?php
    //We'll merge all css and js into sigle files. If you want to use the css separately, you can use it.

/*
    $css = array(
        "assets/js/datatable/datatables.min.css",
        "assets/js/datatable/css/responsive.dataTables.min.css",
        "assets/js/bootstrap-datepicker/css/datepicker3.css",
        "assets/js/bootstrap-timepicker/css/bootstrap-timepicker.min.css",
        "assets/js/dropzone/dropzone.min.css",
        "assets/js/magnific-popup/magnific-popup.css",
        "assets/js/perfect-scrollbar/perfect-scrollbar.css",
        "assets/js/awesomplete/awesomplete.css",
        "assets/js/atwho/css/jquery.atwho.min.css"
    );

    $scss = array(
        "assets/scss/style.scss"
    );

    $js = array(
        "assets/bootstrap/js/bootstrap.bundle.min.js",
        "assets/js/jquery-3.5.1.min.js",
        "assets/js/chartjs/chart.js",
        "assets/js/feather-icons/feather.min.js",
        "assets/js/jquery-validation/jquery.validate.min.js",
        "assets/js/jquery-validation/jquery.form.js",
        "assets/js/perfect-scrollbar/perfect-scrollbar.min.js",
        "assets/js/select2/select2.js",
        "assets/js/datatable/datatables.min.js",
        "assets/js/datatable/js/dataTables.responsive.min.js",
        "assets/js/datatable/js/dataTables.colReorder.min.js",
        "assets/js/datatable/TableTools/js/dataTables.buttons.min.js",
        "assets/js/datatable/TableTools/js/buttons.html5.min.js",
        "assets/js/datatable/TableTools/js/buttons.print.min.js",
        "assets/js/datatable/TableTools/js/jszip.min.js",
        "assets/js/bootstrap-datepicker/js/bootstrap-datepicker.js",
        "assets/js/bootstrap-timepicker/js/bootstrap-timepicker.min.js",
        "assets/js/fullcalendar/moment.min.js",
        "assets/js/dropzone/dropzone.min.js",
        "assets/js/magnific-popup/jquery.magnific-popup.min.js",
        "assets/js/sortable/sortable.min.js",
        "assets/js/atwho/caret/jquery.caret.min.js",
        "assets/js/atwho/js/jquery.atwho.min.js",
        "assets/js/notification_handler.js",
        "assets/js/general_helper.js",
        "assets/js/app.min.js"
    );

    //to merge all files into one, we'll use this helper
    helper('dev_tools');
    write_css($css);
    write_scss($scss);
    write_js($js);

*/

    $css_files = array(
        "assets/bootstrap/css/bootstrap.min.css",
        "assets/js/select2/select2.css", //don't combine this css because of the images path
        "assets/js/select2/select2-bootstrap.min.css",
        "assets/css/app.all.css",
    );

    if (get_setting("rtl")) {
        array_push($css_files, "assets/css/rtl.css");
    }

    array_push($css_files, "assets/css/custom-style.css"); //add to last. custom style should not be merged

    load_css($css_files);

    load_js(array(
        "assets/js/app.all.js"
    ));
    ?>

    <?php echo view("includes/csrf_ajax"); ?>
    <?php echo view("includes/custom_head"); ?>

</head>