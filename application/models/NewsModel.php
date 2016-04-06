<?php

class NewsModel extends AbstractModel
{

    public function getAll()
    {
        return DBAdapter::getInstance()->query('
            SELECT n.id, n.publish_date, n.archive_date, n.allow_comments, n.visible, ncac.name, nco.title
            FROM news n
            JOIN news_content nco ON (nco.news_id = n.id)
            JOIN news_categories_content ncac ON (ncac.news_category_id = n.news_category_id)
            WHERE nco.language_id = :language_id AND ncac.language_id = :language_id
            ORDER BY n.publish_date DESC LIMIT 5
        ', array('language_id' => array('value' => 1, 'type' => PDO::PARAM_INT)));
    }

    public function getSingle($id)
    {
        $result = DBAdapter::getInstance()->query('
            SELECT n.id, n.news_category_id, n.publish_date, n.archive_date, CAST(n.allow_comments AS UNSIGNED) AS allow_comments, CAST(n.visible AS UNSIGNED) AS visible
            FROM news n
            WHERE n.id = :id
            LIMIT 1
        ', array('id' => array('value' => $id, 'type' => PDO::PARAM_INT)));

        $languages = DBAdapter::getInstance()->query('
            SELECT nco.language_id, nco.title, nco.content
            FROM news_content nco
            WHERE nco.news_id = :id
        ', array('id' => array('value' => $id, 'type' => PDO::PARAM_INT)));

        foreach ($languages as $language) {
            $result[0]->language = array($language->language_id => array('title' => $language->title, 'content' => $language->content));
        }

        return $result[0];
    }

    public function getLatest()
    {
        return DBAdapter::getInstance()->query('
            SELECT n.id, n.publish_date, ncac.name, nco.title, nco.content
            FROM news n
            JOIN news_content nco ON (nco.news_id = n.id)
            JOIN news_categories_content ncac ON (ncac.news_category_id = n.news_category_id)
            WHERE nco.language_id = :language_id AND ncac.language_id = :language_id
            ORDER BY n.publish_date DESC LIMIT :limit
        ', array('language_id' => array('value' => 1, 'type' => PDO::PARAM_INT),  'limit' => array('value' => $this->model_config['limit'], 'type' => PDO::PARAM_INT)));
    }

}