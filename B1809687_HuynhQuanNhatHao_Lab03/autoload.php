<?php
    spl_autoload_register(function ($class) {
        // Tiếp đầu ngữ không gian tên.
        // Các lớp trong dự án sẽ sử dụng tiếp đầu ngữ này cho không gian tên
        $prefix = 'CT275\\Lab3\\';
        // Thư mục cơ sở ứng với tiếp đầu ngữ không gian tên
        $base_dir = __DIR__ . '/src/';
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            return;
        }
        $relative_class = substr($class, $len);
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
        if (file_exists($file)) {
            require $file;
        }   
    }
);