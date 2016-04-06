<?php

class ArticleModel extends AbstractModel
{

    public function getAll()
    {
        return DBAdapter::getInstance()->query('
            SELECT a.id, a.publish_date, a.archive_date, a.allow_comments, a.is_online, cc.name, ac.title
            FROM article a
            JOIN article_content ac ON (ac.article_id = a.id)
            JOIN category_content cc ON (cc.category_id = a.category_id)
            WHERE ac.language_id = :language_id AND cc.language_id = :language_id
            ORDER BY a.publish_date
        ', array('language_id' => array('value' => 1, 'type' => PDO::PARAM_INT)));
    }

    public function getSingle($id)
    {
        $result = DBAdapter::getInstance()->query('
            SELECT a.id, a.category_id, a.publish_date, a.archive_date, a.allow_comments, a.is_online
            FROM article a
            WHERE a.id = :id
            LIMIT 1
        ', array('id' => array('value' => $id, 'type' => PDO::PARAM_INT)));

        $languages = DBAdapter::getInstance()->query('
            SELECT ac.language_id, ac.title, ac.content
            FROM article_content ac
            WHERE ac.article_id = :id
        ', array('id' => array('value' => $id, 'type' => PDO::PARAM_INT)));

        foreach ($languages as $language) {
            $result[0]->language[$language->language_id] = array('title' => $language->title, 'content' => $language->content);
        }

        echo '<pre>'; var_dump($result[0]); echo '</pre>';

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