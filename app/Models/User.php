<?php

namespace App\Models;

use Core\Model;

class User extends Model {

    protected static $hidden = [
        'password',
        'remember_token',
    ];

    public function fullname()
    {
        return $this->first_name.' '.$this->last_name;
    }
}
