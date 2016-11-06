<?php

namespace Application\Models;

use ActiveRecord\Model;

class Module extends Model
{

    static $has_many = [
        ['user_roles']
    ];

}
