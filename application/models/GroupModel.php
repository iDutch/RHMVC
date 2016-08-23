<?php

class GroupModel extends AbstractModel
{

    public function getAll()
    {
        return DBAdapter::getInstance()->query('
            SELECT g.id, g.is_enabled, gl.name
            FROM `group` g
            JOIN group_language gl ON (gl.group_id = g.id)
            WHERE gl.language_id = :language_id
            ORDER BY gl.name
        ', array('language_id' => array('value' => $_SESSION['language_id'], 'type' => PDO::PARAM_INT)));
    }

    public function getSingle($id)
    {
        $result = DBAdapter::getInstance()->query('
            SELECT g.id, g.is_enabled
            FROM `group` g
            WHERE g.id = :id
            LIMIT 1
        ', array('id' => array('value' => $id, 'type' => PDO::PARAM_INT)));

        $languages = DBAdapter::getInstance()->query('
            SELECT gl.language_id, gl.name
            FROM group_language gl
            WHERE gl.group_id = :id
        ', array('id' => array('value' => $id, 'type' => PDO::PARAM_INT)));

        foreach ($languages as $language) {
            $result[0]->language[$language->language_id] = array('name' => $language->name);
        }

        $group_roles = DBAdapter::getInstance()->query('
            SELECT gr.module_id, SUM(a.value) AS `value`
            FROM group_role gr
            JOIN `action` a ON (a.id = gr.action_id)
            WHERE gr.group_id = :id
            GROUP BY gr.module_id
        ', array('id' => array('value' => $id, 'type' => PDO::PARAM_INT)));

        $result[0]->group_roles = array();
        foreach ($group_roles as $group_role) {
            $result[0]->group_roles[$group_role->module_id] = $group_role->value;
        }

        return $result[0];
    }

    public function saveGroup($postdata, $id = null)
    {
        $group = array();
        foreach ($postdata as $key => $value) {
            if ($key == 'save' || $key == 'language' || $key == 'group_role') {
                continue;
            }
            $group[$key] = $value;
        }

        DBAdapter::getInstance()->beginTransaction();
        if ($id === null) {
            $id = DBAdapter::getInstance()->insert('group', $group);
        } else {
            DBAdapter::getInstance()->update('group', $group, array('id' => array('value' => $id, 'type' => PDO::PARAM_INT)));
            DBAdapter::getInstance()->delete('group_language', array('group_id' => array('value' => $id, 'type' => PDO::PARAM_INT)));
            DBAdapter::getInstance()->delete('group_role', array('group_id' => array('value' => $id, 'type' => PDO::PARAM_INT)));
        }

        foreach ($postdata['language'] as $language_id => $language) {
            //Set foreign keys correctly
            $postdata['language'][$language_id]['language_id'] = $language_id;
            $postdata['language'][$language_id]['group_id'] = $id;

            DBAdapter::getInstance()->insert('group_language', $postdata['language'][$language_id]);
        }

        foreach ($postdata['group_role'] as $module => $actions) {
            foreach ($actions as $action => $value) {
                DBAdapter::getInstance()->insert('group_role', array('group_id' => $id, 'module_id' => $module, 'action_id' => $action));
            }
        }
        DBAdapter::getInstance()->commit();

    }

    public function getList()
    {
        return DBAdapter::getInstance()->query('
            SELECT g.id, gl.name
            FROM `group` g
            JOIN group_language gl ON (gl.group_id = g.id)
            WHERE gl.language_id = :language_id
            ORDER BY gl.name
        ', array('language_id' => array('value' => $_SESSION['language_id'], 'type' => PDO::PARAM_INT)));
    }

    public function getModuleList()
    {
        return DBAdapter::getInstance()->query('
            SELECT m.id, m.name
            FROM module m
        ');
    }

    public function getActionList()
    {
        return DBAdapter::getInstance()->query('
            SELECT a.id, a.name, a.value
            FROM action a
        ');
    }

    public function getGroupName($id)
    {
        $group = DBAdapter::getInstance()->query('
            SELECT `name`
            FROM `group_language`
            WHERE group_id = :id
            AND language_id = :language_id
            LIMIT 1
        ', array(
            'id' => array('value' => $id, 'type' => PDO::PARAM_INT),
            'language_id' => array('value' => $_SESSION['language_id'], 'type' => PDO::PARAM_INT)
        ));

        return isset($group[0]->name) ? $group[0]->name : false;
    }

}
