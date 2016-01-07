<?php

class CategoryModel extends AbstractModel
{

    public function getAll()
    {
        return DBAdapter::getInstance()->query('
            SELECT nc.id, CAST(nc.enabled AS UNSIGNED) AS enabled, CAST(n.visible AS UNSIGNED) AS visible, ncc.name
            FROM news_categories nc
            JOIN news_categories_content ncc ON (ncc.news_category_id = nc.id)
            WHERE ncc.language_id = :language_id
            ORDER BY ncc.name
        ', array('language_id' => array('value' => 1, 'type' => PDO::PARAM_INT)));
    }

    public function getList()
    {
        return DBAdapter::getInstance()->query('
            SELECT nc.id, ncc.name
            FROM news_categories nc
            JOIN news_categories_content ncc ON (ncc.news_category_id = nc.id)
            WHERE ncc.language_id = :language_id AND enabled = 1
            ORDER BY ncc.name
        ', array('language_id' => array('value' => 1, 'type' => PDO::PARAM_INT)));
    }

}
