<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

define('ROOT', __DIR__);
define('DS', DIRECTORY_SEPARATOR);

function root($filename) {
    return ROOT.DS.$filename;
}

$gtag = root('gtag.php');

if (file_exists($gtag)) {
    include ROOT.DS.'gtag.php';
} else {
    die('gtag.php not found!');
}