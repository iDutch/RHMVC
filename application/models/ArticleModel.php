<?php

class ArticleModel extends AbstractModel
{

    public function getAll()
    {
        return DBAdapter::getInstance()->query('
            SELECT a.id, a.publish_date, a.archive_date, a.allow_comments, a.is_online, cl.name, al.title
            FROM article a
            JOIN article_language al ON (al.article_id = a.id)
            JOIN category_language cl ON (cl.category_id = a.category_id)
            WHERE al.language_id = :language_id AND cl.language_id = :language_id
            ORDER BY a.publish_date
        ', array('language_id' => array('value' => $_SESSION['language_id'], 'type' => PDO::PARAM_INT)));
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
            SELECT al.language_id, al.title, al.content, al.is_online
            FROM article_language al
            WHERE al.article_id = :id
        ', array('id' => array('value' => $id, 'type' => PDO::PARAM_INT)));

        foreach ($languages as $language) {
            $result[0]->language[$language->language_id] = array('title' => $language->title, 'content' => $language->content, 'is_online' => $language->is_online);
        }

        return $result[0];
    }

    public function saveArticle($postdata, $id = null)
    {
        //var_dump($postdata['language']); exit;
        $article = array();
        foreach ($postdata as $key => $value) {
            if ($key == 'save' || $key == 'language' || $key == 'languages') {
                continue;
            }
            $article[$key] = $value;
        }

        if (!isset($article['allow_comments'])) {
            $article['allow_comments'] = 0;
        }
        if (!isset($article['is_online'])) {
            $article['is_online'] = 0;
        }

        DBAdapter::getInstance()->beginTransaction();
        if ($id === null) {
            $id = DBAdapter::getInstance()->insert('article', $article);
        } else {
            DBAdapter::getInstance()->update('article', $article, array('id' => array('value' => $id, 'type' => PDO::PARAM_INT)));
            DBAdapter::getInstance()->delete('article_language', array('article_id' => array('value' => $id, 'type' => PDO::PARAM_INT)));
        }

        foreach ($postdata['language'] as $language_id => $language) {
            //Set foreign keys correctly
            $postdata['language'][$language_id]['language_id'] = $language_id;
            $postdata['language'][$language_id]['article_id'] = $id;

            DBAdapter::getInstance()->insert('article_language', $postdata['language'][$language_id]);
        }
        DBAdapter::getInstance()->commit();

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
        ', array(
            'language_id'   => array('value' => 1, 'type' => PDO::PARAM_INT),
            'limit'         => array('value' => $this->model_config['limit'], 'type' => PDO::PARAM_INT)
        ));
    }

}