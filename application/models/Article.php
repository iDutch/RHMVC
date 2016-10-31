<?php

use ActiveRecord\Model;

class Article extends Model
{

    static $belongs_to = [
        ['category']
    ];
    
    static $has_many = [
        ['article_contents', 'conditions' => ['language_id = ?', 1]]
    ];

}
