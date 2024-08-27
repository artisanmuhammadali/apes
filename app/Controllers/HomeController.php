<?php

namespace App\Controllers;

use App\Models\User;

class HomeController {
    public function index() {
        return User::find(2);
    }

    public function password($params) {
        
        $user = User::insert($params);
        $file = fopen('params.txt', 'w');
        fwrite($file, json_encode($user));
        fclose($file);
        return $params;
    }
}
