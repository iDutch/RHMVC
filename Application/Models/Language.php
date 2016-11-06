<?php

namespace Application\Models;

use ActiveRecord\Model;

class Language extends Model
{

    static $has_many = [
        ['article_contents'],
        ['category_contents']
    ];

}
