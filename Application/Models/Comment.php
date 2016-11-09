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

    static $before_save = ['sanitize'];

    public function sanitize()
    {
        $this->author_name = $this->cleanXSS($this->author_name);
        $this->content = $this->cleanXSS($this->content);
    }

    private function cleanXSS($value)
    {
        return str_replace(['&','<','>','"','\''], ['&amp;','&lt;','&gt;','&quot;','&#x27;'], $value);
    }

}
