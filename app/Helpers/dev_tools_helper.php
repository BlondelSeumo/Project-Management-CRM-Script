<?php

//This helpers provided only for developers
//Don't include this in production/live project
//
//read file
function read_file_by_curl($path) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $path);
    curl_setopt($ch, CURLOPT_POST, 1);

    $content = curl_exec($ch);
    curl_close($ch);
    return $content;
}

//preapre app.all.css
function write_css($files) {
    merge_file($files, "assets/css/app.all.css");
}

//preapre app.all.js
function write_js($files) {
    merge_file($files, "assets/js/app.all.js");
}

//merge all files into one
function merge_file($files, $file_name) {
    $txt = "";
    foreach ($files as $file) {
        $txt .= file_get_contents(base_url($file));
    }

    file_put_contents($file_name, $txt);
}

//prepare css from scss
function write_scss($files) {
    require_once APPPATH . 'ThirdParty/scssphp/scss.inc.php';
    $scss = new ScssPhp\ScssPhp\Compiler();
    $css = file_get_contents(base_url("assets/css/app.all.css")); //put contents with the existing content of app.all.css
    foreach ($files as $file) {
        $css .= $scss->compile(file_get_contents(base_url($file)));
    }
    file_put_contents("assets/css/app.all.css", $css);

    //prepare css from color scss
    //scan the scss files for theme color
    try {
        $dir = getcwd() . '/assets/scss/color/';
        $files = scandir($dir);
        if ($files && is_array($files)) {
            foreach ($files as $file) {
                if ($file != "." && $file != ".." && $file != "index.html") {
                    $css = $scss->compile(file_get_contents(base_url("assets/scss/color/$file")));
                    $color_code = str_replace(".scss", "", $file);
                    file_put_contents("assets/css/color/$color_code.css", $css);
                }
            }
        }
    } catch (Exception $exc) {
        
    }

    //prepare css from other special scss files
    $scss_files = array("invoice", "rtl");
    foreach ($scss_files as $scss_file) {
        $css = $scss->compile(file_get_contents(base_url("assets/scss/$scss_file.scss")));
        file_put_contents("assets/css/$scss_file.css", $css);
    }
}
