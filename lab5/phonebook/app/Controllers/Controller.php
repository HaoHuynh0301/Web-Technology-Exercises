<?php

namespace App\Controllers;

use App\SessionGuard as Guard;
use App\Csrf;

class Controller
{
    protected $view;

    public function __construct()
    {
        $this->view = new \League\Plates\Engine(ROOTDIR.'views');
    }  

    // Kiểm tra CSRF token của form
    protected function invokeCsrfGuard()
    {
        if (! Csrf::verifyToken()) {
            Guard::logout();
            redirect('/login');
        }        
    }    

    // Lưu các giá trị của $_POST vào $_SESSION 
    protected function saveFormValues(array $except = [])
    {
        $form = [];
        foreach($_POST as $key => $value) {
            if (! in_array($key, $except, true)) {
                $form[$key] = $value;
            }
        }
        $_SESSION['form'] = $form; 
    } 
}
