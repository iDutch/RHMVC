<?php

namespace Application\Models;

use ActiveRecord\Model;

class CategoryContent extends Model
{

    static $belongs_to = [
        ['category'],
        ['language']
    ];
    static $validates_presence_of = [
        ['name'],
        ['category_id']
    ];
    static $validates_numericality_of = [
        ['category_id', 'only_integer' => true]
    ];
    static $before_save = ['sanitize'];

    public function sanitize()
    {
        $this->name = $this->cleanXSS($this->name);
    }

    private function cleanXSS($value)
    {
        return str_replace(['&', '<', '>', '"', '\''], ['&amp;', '&lt;', '&gt;', '&quot;', '&#x27;'], $value);
    }

}
