<?php

use ActiveRecord\Model;

class UserRole extends Model
{

    static $belongs_to = [
        ['user', 'foreign_key' => 'users_id'], 
        ['module', 'foreign_key' => 'modules_id'],
        ['action', 'foreign_key' => 'actions_id']
    ];
    
}
