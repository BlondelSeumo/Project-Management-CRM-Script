<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge" >
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="fairsketch">
        <link rel="icon" href="../assets/images/favicon.png" />
        <title>RISE - Ultimate Project Manager Installation</title>
        <link rel='stylesheet' type='text/css' href='../assets/bootstrap/css/bootstrap.min.css' />

        <link rel='stylesheet' type='text/css' href='install.css' />

        <script type='text/javascript'  src='../assets/js/jquery-3.5.1.min.js'></script>
        <script type='text/javascript'  src='../assets/js/feather-icons/feather.min.js'></script>
        <script type='text/javascript'  src='../assets/js/jquery-validation/jquery.validate.min.js'></script>
        <script type='text/javascript'  src='../assets/js/jquery-validation/jquery.form.js'></script>

    </head>
    <body>
        <div class="install-box">

            <div class="card card-install">
                <div class="card-header text-center">                    
                    <h2> RISE - Ultimate Project Manager Installation</h2>
                </div>
                <div class="card-body no-padding">
                    <div class="tab-container clearfix">
                        <div class="container">
                            <div class="row">
                                <div id="pre-installation" class="tab-title col-sm-4 active"><i data-feather="circle" class="icon"></i><strong> Pre-Installation</strong></span></div>
                                <div id="configuration" class="tab-title col-sm-4"><i data-feather="circle" class="icon"></i><strong> Configuration</strong></div>
                                <div id="finished" class="tab-title col-sm-4"><i data-feather="circle" class="icon"></i><strong> Finished</strong></div>
                            </div>
                        </div>
                    </div>
                    <div id="alert-container">

                    </div>


                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="pre-installation-tab">
                            <div class="section">
                                <p>1. Please configure your PHP settings to match following requirements:</p>
                                <hr />
                                <div>
                                    <table>
                                        <thead>
                                            <tr>
                                                <th width="25%">PHP Settings</th>
                                                <th width="27%">Current Version</th>
                                                <th>Required Version</th>
                                                <th class="text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>PHP Version</td>
                                                <td><?php echo $current_php_version; ?></td>
                                                <td><?php echo $php_version_required; ?>+</td>
                                                <td class="text-center">
                                                    <?php if ($php_version_success) { ?>
                                                        <i data-feather="check-circle" class="status-icon"></i>
                                                    <?php } else { ?>
                                                        <i data-feather="x-circle" class="status-icon"></i>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="section">
                                <p>2. Please make sure the extensions/settings listed below are installed/enabled:</p>
                                <hr />
                                <div>
                                    <table>
                                        <thead>
                                            <tr>
                                                <th width="25%">Extension/settings</th>
                                                <th width="27%">Current Settings</th>
                                                <th>Required Settings</th>
                                                <th class="text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>MySQLi</td>
                                                <td> <?php if ($mysql_success) { ?>
                                                        On
                                                    <?php } else { ?>
                                                        Off
                                                    <?php } ?>
                                                </td>
                                                <td>On</td>
                                                <td class="text-center">
                                                    <?php if ($mysql_success) { ?>
                                                        <i data-feather="check-circle" class="status-icon"></i>
                                                    <?php } else { ?>
                                                        <i data-feather="x-circle" class="status-icon"></i>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>GD</td>
                                                <td> <?php if ($gd_success) { ?>
                                                        On
                                                    <?php } else { ?>
                                                        Off
                                                    <?php } ?>
                                                </td>
                                                <td>On</td>
                                                <td class="text-center">
                                                    <?php if ($gd_success) { ?>
                                                        <i data-feather="check-circle" class="status-icon"></i>
                                                    <?php } else { ?>
                                                        <i data-feather="x-circle" class="status-icon"></i>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>cURL</td>
                                                <td> <?php if ($curl_success) { ?>
                                                        On
                                                    <?php } else { ?>
                                                        Off
                                                    <?php } ?>
                                                </td>
                                                <td>On</td>
                                                <td class="text-center">
                                                    <?php if ($curl_success) { ?>
                                                        <i data-feather="check-circle" class="status-icon"></i>
                                                    <?php } else { ?>
                                                        <i data-feather="x-circle" class="status-icon"></i>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>mbstring</td>
                                                <td> <?php if ($mbstring_success) { ?>
                                                        On
                                                    <?php } else { ?>
                                                        Off
                                                    <?php } ?>
                                                </td>
                                                <td>On</td>
                                                <td class="text-center">
                                                    <?php if ($mbstring_success) { ?>
                                                        <i data-feather="check-circle" class="status-icon"></i>
                                                    <?php } else { ?>
                                                        <i data-feather="x-circle" class="status-icon"></i>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>intl</td>
                                                <td> <?php if ($intl_success) { ?>
                                                        On
                                                    <?php } else { ?>
                                                        Off
                                                    <?php } ?>
                                                </td>
                                                <td>On</td>
                                                <td class="text-center">
                                                    <?php if ($intl_success) { ?>
                                                        <i data-feather="check-circle" class="status-icon"></i>
                                                    <?php } else { ?>
                                                        <i data-feather="x-circle" class="status-icon"></i>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>json</td>
                                                <td> <?php if ($json_success) { ?>
                                                        On
                                                    <?php } else { ?>
                                                        Off
                                                    <?php } ?>
                                                </td>
                                                <td>On</td>
                                                <td class="text-center">
                                                    <?php if ($json_success) { ?>
                                                        <i data-feather="check-circle" class="status-icon"></i>
                                                    <?php } else { ?>
                                                        <i data-feather="x-circle" class="status-icon"></i>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>mysqlnd</td>
                                                <td> <?php if ($mysqlnd_success) { ?>
                                                        On
                                                    <?php } else { ?>
                                                        Off
                                                    <?php } ?>
                                                </td>
                                                <td>On</td>
                                                <td class="text-center">
                                                    <?php if ($mysqlnd_success) { ?>
                                                        <i data-feather="check-circle" class="status-icon"></i>
                                                    <?php } else { ?>
                                                        <i data-feather="x-circle" class="status-icon"></i>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>xml</td>
                                                <td> <?php if ($xml_success) { ?>
                                                        On
                                                    <?php } else { ?>
                                                        Off
                                                    <?php } ?>
                                                </td>
                                                <td>On</td>
                                                <td class="text-center">
                                                    <?php if ($xml_success) { ?>
                                                        <i data-feather="check-circle" class="status-icon"></i>
                                                    <?php } else { ?>
                                                        <i data-feather="x-circle" class="status-icon"></i>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>date.timezone</td>
                                                <td> <?php
                                                    if ($timezone_success) {
                                                        echo $timezone_settings;
                                                    } else {
                                                        echo "Null";
                                                    }
                                                    ?>
                                                </td>
                                                <td>Timezone</td>
                                                <td class="text-center">
                                                    <?php if ($timezone_success) { ?>
                                                        <i data-feather="check-circle" class="status-icon"></i>
                                                    <?php } else { ?>
                                                        <i data-feather="x-circle" class="status-icon"></i>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>zlib.output_compression</td>
                                                <td> <?php if ($zlib_success) { ?>
                                                        Off
                                                    <?php } else { ?>
                                                        On
                                                    <?php } ?>
                                                </td>
                                                <td>Off</td>
                                                <td class="text-center">
                                                    <?php if ($zlib_success) { ?>
                                                        <i data-feather="check-circle" class="status-icon"></i>
                                                    <?php } else { ?>
                                                        <i data-feather="x-circle" class="status-icon"></i>
                                                    <?php } ?>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="section">
                                <p>3. Please make sure you have set the <strong>writable</strong> permission on the following folders/files:</p>
                                <hr />
                                <div>
                                    <table>
                                        <tbody>
                                            <?php
                                            foreach ($writeable_directories as $value) {
                                                ?>
                                                <tr>
                                                    <td style="width:87%;"><?php echo $value; ?></td>  
                                                    <td class="text-center">
                                                        <?php if (is_writeable(".." . $value)) { ?>
                                                            <i data-feather="check-circle" class="status-icon"></i>
                                                            <?php
                                                        } else {
                                                            $all_requirement_success = false;
                                                            ?>
                                                            <i data-feather="x-circle" class="status-icon"></i>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button <?php
                                if (!$all_requirement_success) {
                                    echo "disabled=disabled";
                                }
                                ?> class="btn btn-info form-next text-white"><i data-feather="chevron-right" class='icon'></i> Next</button>
                            </div>

                        </div>
                        <div role="tabpanel" class="tab-pane" id="configuration-tab">
                            <form name="config-form" id="config-form" action="do_install.php" method="post">

                                <div class="section clearfix">
                                    <p>1. Please enter your database connection details.</p>
                                    <hr />
                                    <div>
                                        <div class="form-group clearfix">
                                            <div class="row">
                                                <label for="host" class=" col-md-3">Database Host</label>
                                                <div class="col-md-9">
                                                    <input type="text" value="" id="host"  name="host" class="form-control" placeholder="Database Host (usually localhost)" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group clearfix">
                                            <div class="row">
                                                <label for="dbuser" class=" col-md-3">Database User</label>
                                                <div class=" col-md-9">
                                                    <input id="dbuser" type="text" value="" name="dbuser" class="form-control" autocomplete="off" placeholder="Database user name" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group clearfix">
                                            <div class="row">
                                                <label for="dbpassword" class=" col-md-3">Password</label>
                                                <div class=" col-md-9">
                                                    <input id="dbpassword" type="password" value="" name="dbpassword" class="form-control" autocomplete="off" placeholder="Database user password" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group clearfix">
                                            <div class="row">
                                                <label for="dbname" class=" col-md-3">Database Name</label>
                                                <div class=" col-md-9">
                                                    <input id="dbname" type="text" value="" name="dbname" class="form-control" placeholder="Database Name" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="section clearfix">
                                    <p>2. Please enter your account details for administration.</p>
                                    <hr />
                                    <div>
                                        <div class="form-group clearfix">
                                            <div class="row">
                                                <label for="first_name" class=" col-md-3">First Name</label>
                                                <div class="col-md-9">
                                                    <input type="text" value=""  id="first_name"  name="first_name" class="form-control"  placeholder="Your first name" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group clearfix">
                                            <div class="row">
                                                <label for="last_name" class=" col-md-3">Last Name</label>
                                                <div class=" col-md-9">
                                                    <input type="text" value="" id="last_name"  name="last_name" class="form-control"  placeholder="Your last name" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group clearfix">
                                            <div class="row">
                                                <label for="email" class=" col-md-3">Email</label>
                                                <div class=" col-md-9">
                                                    <input id="email" type="text" value="" name="email" class="form-control" placeholder="Your email" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group clearfix">
                                            <div class="row">
                                                <label for="password" class=" col-md-3">Password</label>
                                                <div class=" col-md-9">
                                                    <input id="password" type="password" value="" name="password" class="form-control" placeholder="Login password" />
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="section clearfix">
                                    <p>3. Please enter your item purchase code.</p>
                                    <hr />
                                    <div>
                                        <div class="form-group clearfix">
                                            <div class="row">
                                                <label for="purchase_code" class=" col-md-3">Item purchase code</label>
                                                <div class="col-md-9">
                                                    <input type="text" value=""  id="purchase_code"  name="purchase_code" class="form-control"  placeholder="Find in codecanyon item download section" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-info form-next">
                                        <span class="loader text-white hide"><span> Please wait...</span></span>
                                        <span class="button-text text-white"><i data-feather="chevron-right" class='icon'></i> Finish</span> 
                                    </button>
                                </div>

                            </form>
                        </div>

                        <div role="tabpanel" class="tab-pane" id="finished-tab">
                            <div class="section">
                                <div class="clearfix">
                                    <i data-feather="check-circle" height="2.5rem" width="2.5rem" stroke-width="3" class='status mr10'></i><span class="pull-left"  style="line-height: 50px;">Congratulation! You have successfully installed RISE - Ultimate Project Manager</span>  
                                </div>

                                <div style="margin: 15px 0 15px 55px; color: #d73b3b;">
                                    Don't forget to delete the <b>install</b> directory!
                                </div>
                                <a class="go-to-login-page" href="<?php echo $dashboard_url; ?>">
                                    <div class="text-center">
                                        <div style="font-size: 100px;"><i data-feather="monitor" height="7rem" width="7rem" class="mb-2"></i></div>
                                        <div>GO TO YOUR LOGIN PAGE</div>
                                    </div>
                                </a>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

<script type="text/javascript">

    var onFormSubmit = function ($form) {
        $form.find('[type="submit"]').attr('disabled', 'disabled').find(".loader").removeClass("hide");
        $form.find('[type="submit"]').find(".button-text").addClass("hide");
        $("#alert-container").html("");
    };
    var onSubmitSussess = function ($form) {
        $form.find('[type="submit"]').removeAttr('disabled').find(".loader").addClass("hide");
        $form.find('[type="submit"]').find(".button-text").removeClass("hide");
    };

    feather.replace();

    $(document).ready(function () {
        var $preInstallationTab = $("#pre-installation-tab"),
                $configurationTab = $("#configuration-tab");

        $(".form-next").click(function () {
            if ($preInstallationTab.hasClass("active")) {
                $preInstallationTab.removeClass("active");
                $configurationTab.addClass("active");
                $("#pre-installation").find("svg").remove();
                $("#pre-installation").prepend('<i data-feather="check-circle" class="icon"></i>');
                feather.replace();
                $("#configuration").addClass("active");
                $("#host").focus();
            }
        });

        $("#config-form").submit(function () {
            var $form = $(this);
            onFormSubmit($form);
            $form.ajaxSubmit({
                dataType: "json",
                success: function (result) {
                    onSubmitSussess($form, result);
                    if (result.success) {
                        $configurationTab.removeClass("active");
                        $("#configuration").find("svg").remove();
                        $("#configuration").prepend('<i data-feather="check-circle" class="icon"></i>');
                        $("#finished").find("svg").remove();
                        $("#finished").prepend('<i data-feather="check-circle" class="icon"></i>');
                        feather.replace();
                        $("#finished").addClass("active");
                        $("#finished-tab").addClass("active");
                    } else {
                        $("#alert-container").html('<div class="alert alert-danger" role="alert">' + result.message + '</div>');
                    }
                }
            });
            return false;
        });

    });
</script>