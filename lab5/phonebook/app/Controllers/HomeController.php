<?php

namespace App\Controllers;

use App\Models\User;
use App\SessionGuard as Guard;

class HomeController extends Controller
{
    public function __construct()
    {
        // Nếu người dùng chưa đăng nhập thì chuyển hướng đến đăng nhập
        if (! Guard::checkLogin()) {
            redirect('/login');
        }

        parent::__construct();
    }

    public function index()
    {
        // Thu thập dữ liệu cho view
        $data = [];

        // Tạo và hiển thị view
        echo $this->view->render('home', $data);
    }
}
