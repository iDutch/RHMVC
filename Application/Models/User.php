<?php

namespace Application\Models;

use ActiveRecord\Model;

class User extends Model
{

    static $belongs_to = [
        ['group']
    ];

    static $has_many = [
        ['user_roles']
    ];

    public function authenticate($password)
    {
        if (password_verify($password, $this->password)) {
            $_SESSION['user'] = $this;
            session_regenerate_id();
            return true;
        }
        return false;
    }

}
