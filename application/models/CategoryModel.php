<?php

class CategoryModel extends AbstractModel
{

    public function getAll()
    {
        return DBAdapter::getInstance()->query('
            SELECT c.id, c.is_enabled, c.is_online, cl.name
            FROM category c
            JOIN category_language cl ON (cl.category_id = c.id)
            WHERE cl.language_id = :language_id
            ORDER BY cl.name
        ', array('language_id' => array('value' => $_SESSION['language_id'], 'type' => PDO::PARAM_INT)));
    }

    public function getSingle($id)
    {
        $result = DBAdapter::getInstance()->query('
            SELECT c.id, c.is_enabled, c.is_online
            FROM category c
            WHERE c.id = :id
            LIMIT 1
        ', array('id' => array('value' => $id, 'type' => PDO::PARAM_INT)));

        $languages = DBAdapter::getInstance()->query('
            SELECT cl.language_id, cl.name, cl.is_online
            FROM category_language cl
            WHERE cl.category_id = :id
        ', array('id' => array('value' => $id, 'type' => PDO::PARAM_INT)));

        foreach ($languages as $language) {
            $result[0]->language[$language->language_id] = array('name' => $language->name, 'is_online' => $language->is_online);
        }

        return $result[0];
    }

    public function saveCategory($postdata, $id = null)
    {
        $category = array();
        foreach ($postdata as $key => $value) {
            if ($key == 'save' || $key == 'language' || $key == 'languages') {
                continue;
            }
            $category[$key] = $value;
        }

        if (!isset($category['is_enabled'])) {
            $category['is_enabled'] = 0;
        }
        if (!isset($category['is_online'])) {
            $category['is_online'] = 0;
        }

        DBAdapter::getInstance()->beginTransaction();
        if ($id === null) {
            $id = DBAdapter::getInstance()->insert('category', $category);
        } else {
            DBAdapter::getInstance()->update('category', $category, array('id' => array('value' => $id, 'type' => PDO::PARAM_INT)));
            DBAdapter::getInstance()->delete('category_language', array('category_id' => array('value' => $id, 'type' => PDO::PARAM_INT)));
        }

        foreach ($postdata['language'] as $language_id => $language) {
            //Set foreign keys correctly
            $postdata['language'][$language_id]['language_id'] = $language_id;
            $postdata['language'][$language_id]['category_id'] = $id;

            DBAdapter::getInstance()->insert('category_language', $postdata['language'][$language_id]);
        }
        DBAdapter::getInstance()->commit();

    }

    public function getList()
    {
        return DBAdapter::getInstance()->query('
            SELECT c.id, cl.name
            FROM category c
            JOIN category_language cl ON (cl.category_id = c.id)
            WHERE cl.language_id = :language_id AND c.is_enabled = 1
            ORDER BY cl.name
        ', array('language_id' => array('value' => 1, 'type' => PDO::PARAM_INT)));
    }

}
