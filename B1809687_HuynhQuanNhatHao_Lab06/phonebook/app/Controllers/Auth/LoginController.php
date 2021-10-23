<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Controllers\Controller;
use App\SessionGuard as Guard;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // Nếu người dùng đã đăng nhập thì không hiện trang đăng nhập
        if (Guard::checkLogin()) {
            redirect('/home');
        }

        // Thu thập dữ liệu cho view
        $data = [
            'messages' => session_get_once('messages'),
            'old' => session_get_once('form'),
            'errors' => session_get_once('errors')
        ];             

        // Tạo và hiển thị view
        echo $this->view->render('auth/login', $data);
    }

    public function login()
    {
        // Ngăn ngừa tấn công CSRF
        $this->invokeCsrfGuard();

        // Đọc giá trị của form
        $userCredentials = $this->getUserCredentials();

        $errors = [];
        $user = User::where('email', $userCredentials['email'])->first();
        if ($user === null) {
            // Người dùng không tồn tại...
            $errors['email'] = 'Unknown email.';
        } else if (Guard::login($user, $userCredentials)) {
            // Đăng nhập thành công...
            redirect('/home');
        } else {
            // Sai mật khẩu...
            $errors['password'] = 'Incorrect password.';
        }

        // Đăng nhập không thành công...
        $this->saveFormValues(['password']);
        redirect('/login', ['errors' => $errors]);
    }

    public function logout()
    {
        Guard::logout();
        redirect('/login');
    }

    protected function getUserCredentials()
    {
        return [
            'email' => filter_var($_POST['email'], FILTER_VALIDATE_EMAIL),
            'password' => $_POST['password']
        ];        
    }
}
