<?php

use ActiveRecord\Model;

class UserRole extends Model
{

    static $belongs_to = [
        ['user'], 
        ['module'],
        ['action']
    ];
    
}
