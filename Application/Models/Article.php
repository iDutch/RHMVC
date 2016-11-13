<?php

namespace Application\Models;

use ActiveRecord\Model;

class Article extends Model
{

    static $belongs_to = [
            ['category']
    ];
    static $has_many = [
            ['article_contents', 'conditions' => ['language_id = ?', 1]]
    ];
    static $validates_presence_of = [
            ['publish_date'],
            ['category_id']
    ];

}
