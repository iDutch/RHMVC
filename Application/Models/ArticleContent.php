<?php

namespace Application\Models;

use ActiveRecord\Model;

class ArticleContent extends Model
{

    static $belongs_to = [
            ['article'],
            ['language']
    ];
    static $validates_presence_of = [
            ['title'],
            ['content']
    ];
    static $before_save = ['sanitize'];

    public function sanitize()
    {
        $this->title = $this->cleanXSS($this->title);
        $this->content = $this->cleanXSS($this->content);
    }

    private function cleanXSS($value)
    {
        return str_replace(['&', '<', '>', '"', '\''], ['&amp;', '&lt;', '&gt;', '&quot;', '&#x27;'], $value);
    }

}
