<?php

class NewsModel extends AbstractModel
{

    public function __construct()
    {
        $this->loadModelConfig(__CLASS__);
    }

    public function getLatest()
    {
        return DBAdapter::getInstance()->query('
            SELECT n.id, n.publish_date, ncac.name, nco.title, nco.content
            FROM news n
            JOIN news_content nco ON (nco.news_id = n.id)
            JOIN news_category_content ncac ON (ncac.news_category_id = n.news_category_id)
            WHERE nco.language_id = :language_id AND ncac.language_id = :language_id
            ORDER BY n.publish_date DESC LIMIT 5
        ', array('language_id' => 1));
    }

}