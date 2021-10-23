<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contacts';
    protected $fillable = ['name', 'phone', 'notes', 'user_id'];
    protected $errors = [];

    public function validate(array $data)
    {
        if (! $data['name']) {
            $this->errors['name'] = 'Name is required.';
        }

        if (strlen($data['phone']) < 10 || strlen($data['phone']) > 11) {
            $this->errors['phone'] = 'Invalid phone number.';
        }

        if (strlen($data['notes']) > 255) {
            $this->errors['notes'] = 'Notes must be at most 255 characters.';
        }
        
        return (count($this->errors) > 0 ? false : true);
    }

    public function getErrors()
    {
        return $this->errors;
    }  
}