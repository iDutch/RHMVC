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

}
