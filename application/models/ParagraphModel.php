<?php

class ParagraphModel extends AbstractModel
{

    public function getAll()
    {
        return DBAdapter::getInstance()->query('
            SELECT p.id, p.is_online, pl.title
            FROM paragraph p
            JOIN paragraph_language pl ON (pl.language_id = p.id)
            WHERE cc.language_id = :language_id
            ORDER BY cc.name
        ', array('language_id' => array('value' => $_SESSION['language_id'], 'type' => PDO::PARAM_INT)));
    }

    public function getSingle($id)
    {
        $result = DBAdapter::getInstance()->query('
            SELECT p.id, p.is_online
            FROM paragraph p
            WHERE p.id = :id
            LIMIT 1
        ', array('id' => array('value' => $id, 'type' => PDO::PARAM_INT)));

        $languages = DBAdapter::getInstance()->query('
            SELECT pl.language_id, pl.title, pl.content, pl.is_online
            FROM paragraph_language pl
            WHERE pl.paragraph_id = :id
        ', array('id' => array('value' => $id, 'type' => PDO::PARAM_INT)));

        foreach ($languages as $language) {
            $result[0]->language[$language->language_id] = array('name' => $language->name, 'is_online' => $language->is_online);
        }

        return $result[0];
    }

    public function saveParagraph($postdata, $id = null)
    {
        $paragraph = array();
        foreach ($postdata as $key => $value) {
            if ($key == 'save' || $key == 'language' || $key == 'languages') {
                continue;
            }
            $paragraph[$key] = $value;
        }

        if ($id === null) {
            $id = DBAdapter::getInstance()->insert('paragraph', $paragraph);
        } else {
            DBAdapter::getInstance()->update('paragraph', $paragraph, array('id' => array('value' => $id, 'type' => PDO::PARAM_INT)));
            DBAdapter::getInstance()->delete('paragraph_language', array('paragraph_id' => array('value' => $id, 'type' => PDO::PARAM_INT)));
        }

        foreach ($postdata['language'] as $language_id => $language) {
            //Set foreign keys correctly
            $postdata['language'][$language_id]['language_id'] = $language_id;
            $postdata['language'][$language_id]['paragraph_id'] = $id;

            DBAdapter::getInstance()->insert('paragraph_language', $postdata['language'][$language_id]);
        }

    }

}
