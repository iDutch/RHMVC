<?php

namespace Application\Models;

use ActiveRecord\Model;

class Group extends Model
{

    static $has_many = [
        ['users']
    ];

}
