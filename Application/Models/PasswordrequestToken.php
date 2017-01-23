<?php

namespace Application\Models;

use ActiveRecord\Model;

class PasswordrequestToken extends Model
{

    static $belongs_to = [
        ['user']
    ];

}
