<?php

namespace Application\Models;

use ActiveRecord\Model;

class CategoryContent extends Model
{

    static $belongs_to = [
        ['category'],
        ['language']
    ];

}
