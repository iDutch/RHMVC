<?php

class CategoryModel extends AbstractModel
{

    public function getAll()
    {
        return DBAdapter::getInstance()->query('
            SELECT c.id, c.is_enabled, c.is_online, cc.name
            FROM category c
            JOIN category_content cc ON (cc.category_id = c.id)
            WHERE cc.language_id = :language_id
            ORDER BY cc.name
        ', array('language_id' => array('value' => 1, 'type' => PDO::PARAM_INT)));
    }

    public function getList()
    {
        return DBAdapter::getInstance()->query('
            SELECT c.id, cc.name
            FROM category c
            JOIN category_content cc ON (cc.category_id = c.id)
            WHERE cc.language_id = :language_id AND c.is_enabled = 1
            ORDER BY cc.name
        ', array('language_id' => array('value' => 1, 'type' => PDO::PARAM_INT)));
    }

}
