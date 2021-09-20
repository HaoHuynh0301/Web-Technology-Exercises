<?php
define('DEBUG', true);
if (DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
session_start();
require __DIR__ . "/autoload.php";
require __DIR__ . "/src/helpers.php";