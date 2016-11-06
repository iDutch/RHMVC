<?php

namespace Application\Models;

use ActiveRecord\Model;

class Comment extends Model
{

    static $belongs_to = [
        ['article'],
        ['user']
    ];

    static $validates_presence_of = [
        ['author_name', 'allow_null' => true],
        ['content']
    ];

}
