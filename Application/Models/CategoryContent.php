<?php

namespace Application\Models;

use System\Core\RHMVC\AbstractModel;

class CategoryContent extends AbstractModel
{

    static $belongs_to = [
        ['category'],
        ['language']
    ];
    static $validates_presence_of = [
        ['name', 'message' => 'Category name cannot be empty'],
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
