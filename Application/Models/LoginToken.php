<?php

namespace Application\Models;

use ActiveRecord\Model;

class LoginToken extends Model
{

    static $belongs_to = [
        ['user']
    ];

}
