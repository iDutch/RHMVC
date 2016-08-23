<?php

trait ACLTrait
{

    private function getModuleId($classname)
    {
        $module = DBAdapter::getInstance()->query('
            SELECT m.id
            FROM module m
            WHERE m.controller = :controller
            LIMIT 1
        ', array('controller' => array('value' => $classname)));

        return $module[0]->id;
    }

    private function getActionValue($method)
    {
        $action = DBAdapter::getInstance()->query('
            SELECT a.value
            FROM `action` a
            WHERE a.name = :name
            LIMIT 1
        ', array('name' => array('value' => $method)));

        return $action[0]->value;
    }

    private function hasAccess($method)
    {
        $method = strtolower(str_replace("Action", "", str_replace("admin_", "", $method)));
        $action_value = $this->getActionValue($method);
        $module_id = $this->getModuleId(__CLASS__); //Will contain the the name of the class in which this function is called

        //Check user's policies
        if ((isset($_SESSION['user']->group_roles[$module_id]) && ((int) $_SESSION['user']->group_roles[$module_id] & $action_value)) || (isset($_SESSION['user']->user_roles[$module_id]) && ((int) $_SESSION['user']->user_roles[$module_id] & $action_value))) {
            return true;
        }
        return false;
    }

}

