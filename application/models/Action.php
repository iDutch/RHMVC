<?php

use ActiveRecord\Model;

class Action extends Model
{

    static $has_many = [
        ['user_roles']
    ];

}
