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

    public function notFound()
    {
        if (isset($_SERVER['HTTP_ACCEPT']) &&
            ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/json') !== false))) {
            send_json_fail(['message' => 'Not Found'], 404);
        } 

        http_response_code(404);
        echo $this->view->render('errors/404');
        exit();   
    }    

    public function internalServerError()
    {
        $lastError = error_get_last();
        if($lastError && $lastError['type'] === E_ERROR) {  
            $trace = $lastError['message'] . ' in ' . $lastError['file'] . ' on line ' . $lastError['line'];
            if (isset($_SERVER['HTTP_ACCEPT']) &&
                ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/json') !== false))) {
                send_json_error('Internal Server Error', DEBUG ? ['error_details' => $trace] : null, 500);
            } 
            
            http_response_code(500);
            $trace = preg_replace("/\n/", '<br>', $trace);                
            echo $this->view->render('errors/500', ['trace' => DEBUG ? $trace : null]);
            exit();     
        }
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
