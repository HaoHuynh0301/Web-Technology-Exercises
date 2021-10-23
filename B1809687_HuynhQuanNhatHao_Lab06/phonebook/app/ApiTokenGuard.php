<?php

namespace App;

use App\Models\User;

class ApiTokenGuard
{
    private $user;

    public function verifyToken()
    {
        if (!isset($_SERVER['HTTP_X_PUBLIC']) || !isset($_SERVER['HTTP_X_HASH'])) {
            return false;
        }

        $publicHash = $_SERVER['HTTP_X_PUBLIC'];
        $contentHash = $_SERVER['HTTP_X_HASH'];


        $this->user = User::where('public_hash', $publicHash)->first();
        if (!$this->user) {
            return false;
        }

        $body = file_get_contents('php://input');

        $data = strtolower($_SERVER['REQUEST_METHOD'] . $_SERVER['REQUEST_URI'] . $body);
        $hash = hash_hmac('sha256', $data, $this->user->private_hash);
        return hash_equals($contentHash, $hash);
    }

    public function getUser()
    {
        return $this->user;
    }

}