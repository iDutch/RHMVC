<?php

namespace Application\Controllers;

trait PermissionTrait
{
    private static $ALLOW_READ = 1;
    private static $ALLOW_CREATE = 2;
    private static $ALLOW_UPDATE = 4;
    private static $ALLOW_DELETE = 8;

    protected function hasPermission($permission)
    {
        if (isset($_SESSION['user']['user_roles']) && (intval($_SESSION['user']['user_roles'][(new \ReflectionClass($this))->getShortName()]) & intval($permission)) != 0) {
            return true;
        }
        return false;
    }

    protected function getPermissionsArray()
    {
        $permissions = [];
        foreach ($this->get_static_vars((new \ReflectionClass($this))->getName()) as $key => $value) {
            $permissions[$key] = $this->hasPermission($value);
        }
        return $permissions;
    }

    private function get_static_vars($class) {
        $result = [];
        foreach (get_class_vars($class) as $name => $default) {
            if (isset($class::$$name)) {
                $result[strtolower(substr($name, 6))] = $default;
            }
        }
        return $result;
    }
}