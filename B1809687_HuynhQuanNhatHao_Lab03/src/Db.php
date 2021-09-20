<?php
    namespace CT275\Lab3;
    class Db {
        private static $instance = NULL;
        private function __construct() {}
        private function __clone() {}
        public static function getInstance() {
            if (!isset(self::$instance)) {
                $driver = 'mysql';
                $host = 'localhost';
                $name = 'ct275_lab3db';
                $options[\PDO::ATTR_ERRMODE] = \PDO::ERRMODE_EXCEPTION;
                $dsn = "$driver:host=$host;dbname=$name;charset=utf8";
                self::$instance = new \PDO($dsn, 'root', '', $options);
            }
            return self::$instance;
    }
}