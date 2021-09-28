<?php
    define('DEBUG', true);
    if (DEBUG) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }

    session_start();
    require __DIR__ . "/vendor/autoload.php";
    use \Illuminate\Database\Capsule\Manager as DbManager;
    
    $dbManager = new DbManager();
    $dbManager->addConnection([
        'driver' => 'mysql',
        'host' => 'localhost:3307',
        'database' => 'ct275_lab4db',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
    ]);
    $dbManager->setAsGlobal();
    $dbManager->bootEloquent();