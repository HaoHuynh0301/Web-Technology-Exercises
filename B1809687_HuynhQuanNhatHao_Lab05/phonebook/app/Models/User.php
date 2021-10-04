<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password'];
    protected $errors = [];

    public function validate(array $data)
    {
        if (! $data['email']) {
            $this->errors['email'] = 'Invalid email.';
        } elseif (static::where('email', $data['email'])->count() > 0) {
            $this->errors['email'] = 'Email already in use.';
        }    

        if (strlen($data['password']) < 6) {
            $this->errors['password'] = 'Password must be at least 6 characters.';
        } elseif ($data['password'] != $data['password_confirmation']) {
            $this->errors['password'] = 'Password confirmation does not match.';
        }
        
        return (count($this->errors) > 0 ? false : true);
    }

    public function getErrors()
    {
        return $this->errors;
    }     
}