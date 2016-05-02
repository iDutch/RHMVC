<?php

class UserModel extends AbstractModel
{

    public function getAll()
    {
        return DBAdapter::getInstance()->query('
            SELECT u.id, u.username, u.emailaddress, gl.name, u.is_enabled
            FROM `user` u
            JOIN `group_language` gl ON (gl.group_id = u.group_id)
            WHERE gl.language_id = :language_id
            ORDER BY u.id
        ', array('language_id' => array('value' => $_SESSION['language_id'], 'type' => PDO::PARAM_INT)));
    }

    public function getSingle($id)
    {
        $result = DBAdapter::getInstance()->query('
            SELECT u.id, u.username, u.emailaddress, u.password, u.group_id, u.is_enabled
            FROM `user` u
            WHERE u.id = :id
            LIMIT 1
        ', array('id' => array('value' => $id, 'type' => PDO::PARAM_INT)));

        $user_roles = DBAdapter::getInstance()->query('
            SELECT ur.module_id, SUM(a.value) AS `value`
            FROM user_role ur
            JOIN `action` a ON (a.id = ur.action_id)
            WHERE ur.user_id = :id
            GROUP BY ur.module_id
        ', array('id' => array('value' => $id, 'type' => PDO::PARAM_INT)));

        $result[0]->user_roles = array();
        foreach ($user_roles as $user_role) {
            $result[0]->user_roles[$user_role->module_id] = $user_role->value;
        }

        return $result[0];
    }

    public function saveUser($postdata, $id = null)
    {
        $user = array();
        foreach ($postdata as $key => $value) {
            if ($key == 'save' || $key == 'user_role') {
                continue;
            }
            $user[$key] = $value;
        }

        if (!$this->isUniqueEmail($user['emailaddress']) || !$this->isUniqueUsername($user['username'])) {
            if (!$this->isUniqueEmail($user['emailaddress'])) {
                $this->flashMessage()->error('email address already exists', null, null);
            }
            if (!$this->isUniqueUsername($user['username'])) {
                $this->flashMessage()->error('username already exists', null, null);
            }
            return false;
        }

        if (!isset($user['is_enabled'])) {
            $user['is_enabled'] = 0;
        }

        DBAdapter::getInstance()->beginTransaction();
        if ($id === null) {
            $id = DBAdapter::getInstance()->insert('user', $user);
        } else {
            DBAdapter::getInstance()->update('user', $user, array('id' => array('value' => $id, 'type' => PDO::PARAM_INT)));
            DBAdapter::getInstance()->delete('user_role', array('user_id' => array('value' => $id, 'type' => PDO::PARAM_INT)));
        }

        foreach ($postdata['user_role'] as $module => $actions) {
            foreach ($actions as $action => $value) {
                DBAdapter::getInstance()->insert('user_role', array('user_id' => $id, 'module_id' => $module, 'action_id' => $action));
            }
        }
        DBAdapter::getInstance()->commit();
        Logger::getInstance()->log((isset($id) ? 'update' : 'create'), 'user', $id);

        return $id;
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

    private function isUniqueUsername($username)
    {
        $users =  DBAdapter::getInstance()->query('
            SELECT u.id
            FROM `user` u
            WHERE LOWER(u.username) = :username
        ', array('username' => array('value' => strtolower($username), 'type' => PDO::PARAM_STR)));

        return count($users) < 1;
    }

    private function isUniqueEmail($emailaddress)
    {
        $users =  DBAdapter::getInstance()->query('
            SELECT u.id
            FROM `user` u
            WHERE LOWER(u.emailaddress) = :emailaddress
        ', array('emailaddress' => array('value' => strtolower($emailaddress), 'type' => PDO::PARAM_STR)));

        return count($users) < 1;
    }

    public function authenticateUser($postdata) {

        $userdata = array();
        foreach ($postdata as $key => $value) {
            if ($key == 'login' || $key == 'remember') {
                continue;
            }
            $userdata[$key] = $value;
        }

        $user = DBAdapter::getInstance()->query('
            SELECT u.id, u.username, u.emailaddress, u.password, u.group_id, u.is_enabled
            FROM `user` u
            WHERE (u.username = :username OR u.emailaddress = :username)
            AND u.is_enabled = 1
            LIMIT 1
        ', array('username' => array('value' => $userdata['username'], 'type' => PDO::PARAM_STR)));

        if (count($user) < 1) {
            $this->flashMessage()->error('invalid username', null, null);
            return false;
        }

        if (!password_verify($userdata['password'], $user[0]->password)) {
            $this->flashMessage()->error('invalid password', null, null);
            return false;
        }

        $user_roles = DBAdapter::getInstance()->query('
            SELECT ur.module_id, SUM(a.value) AS `value`
            FROM user_role ur
            JOIN `action` a ON (a.id = ur.action_id)
            WHERE ur.user_id = :id
            GROUP BY ur.module_id
        ', array('id' => array('value' => $user[0]->id, 'type' => PDO::PARAM_INT)));

        $group_roles = DBAdapter::getInstance()->query('
            SELECT gr.module_id, SUM(a.value) AS `value`
            FROM group_role gr
            JOIN `action` a ON (a.id = gr.action_id)
            WHERE gr.group_id = :id
            GROUP BY gr.module_id
        ', array('id' => array('value' => $user[0]->group_id, 'type' => PDO::PARAM_INT)));

        foreach ($user_roles AS $user_role) {
            $user[0]->user_roles[$user_role->module_id] = $user_role->value;
        }
        foreach ($group_roles AS $group_role) {
            $user[0]->group_roles[$group_role->module_id] = $group_role->value;
        }
        $_SESSION['user'] = $user[0];

        return true;
    }

}
