<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Controllers\Controller;
use App\SessionGuard as Guard;

class RegisterController extends Controller
{
    public function __construct()
    {
        // Nếu người dùng đã đăng nhập thì không cho đăng ký
        if (Guard::checkLogin()) {
            redirect('/home');
        }
        
        parent::__construct();
    }

    public function showRegisterForm()
    {
        // Thu thập dữ liệu cho view
        $data = [
            'old' => session_get_once('form'),
            'errors' => session_get_once('errors')
        ];  

        // Tạo và hiển thị view
        echo $this->view->render('auth/register', $data);
    }

    public function register()
    {
        // Ngăn ngừa tấn công CSRF
        $this->invokeCsrfGuard();

        // Đọc giá trị của form
        $data = $this->getUserData();

        $this->saveFormValues(['password', 'password_confirmation']);

        $user = new User();
        if ($user->validate($data)) {
            // Dữ liệu hợp lệ...
            $this->createUser($data);
            $messages = ['success' => 'User has been created successfully.'];
            redirect('/login', ['messages' => $messages]);
        }

        // Dữ liệu không hợp lệ...
        redirect('/register', ['errors' => $user->getErrors()]);
    }

    protected function getUserData()
    {
        return [
            'name' => filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING),
            'email' => filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL),
            'password' => $_POST['password'],
            'password_confirmation' => $_POST['password_confirmation']
        ];
    }

    protected function createUser($data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT)
        ]);    
    }
}
