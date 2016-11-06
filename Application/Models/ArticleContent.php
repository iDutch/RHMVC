<?php

namespace Application\Models;

use ActiveRecord\Model;

class ArticleContent extends Model
{

    static $belongs_to = [
        ['article'],
        ['language']
    ];

}
