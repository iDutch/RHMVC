<?php

namespace Application\Models;

use ActiveRecord\Model;

class Action extends Model
{

    static $has_many = [
        ['user_roles']
    ];

    static $belongs_to = [
        ['module', 'through' => 'user_roles']
    ];

}
