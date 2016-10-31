<?php

use ActiveRecord\Model;

class ArticleContent extends Model 
{
    
    static $belongs_to = [
        ['article'],
        ['language']
    ];
    
}
