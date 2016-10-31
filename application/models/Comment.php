<?php

use ActiveRecord\Model;

class Comment extends Model
{

    static $belongs_to = [
        ['article']
    ];

}
