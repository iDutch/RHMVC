<?php

namespace Application\Models;

use ActiveRecord\Model;

class Category extends Model
{

    static $table_name = 'categories';

    static $has_many = [
        ['articles'],
        ['category_contents', 'conditions' => ['language_id = ?', 1]]
    ];

}
