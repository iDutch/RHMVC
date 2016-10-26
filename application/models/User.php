<?php

use ActiveRecord\Model;

class User extends Model
{

    static $belongs_to = [
        ['group', 'foreign_key' => 'groups_id']
    ];

}
