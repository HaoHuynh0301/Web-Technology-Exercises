<?php

namespace App;

class Csrf
{
    public static function generateToken($length = 32)
    {
        if(function_exists('random_bytes')) {
            $_SESSION['csrf']['token'] = bin2hex(random_bytes($length));
        } else {
            $_SESSION['csrf']['token'] = bin2hex(openssl_random_pseudo_bytes($length));
        }
        
        return $_SESSION['csrf']['token'];
    }

    public static function getToken()
    {
        if (! isset($_SESSION['csrf']['token'])) {
            static::generateToken();
        }
        return $_SESSION['csrf']['token'];
    }

    public static function verifyToken() 
    {
        $input = array_merge($_GET, $_POST);
        if (isset($input['_csrf_token']) &&
                isset($_SESSION['csrf']['token'])) {
            return ($input['_csrf_token'] == $_SESSION['csrf']['token']);
        }
        return false;
    }
}
