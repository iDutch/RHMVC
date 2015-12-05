<?php

abstract class AbstractController
{

    protected $layout;

    public function __construct($layout)
    {
        $this->layout = $layout;
    }

    protected function invokeController($controller, $action, array $params = array())
    {
        if (file_exists(__DIR__ . '/../../../application/controllers/' . $controller . '.php')) {
            require_once __DIR__ . '/../../../application/controllers/' . $controller . '.php';
            $controller = new $controller($this->layout);
            return call_user_func_array(array($controller, $action), $params);
        } else {
            throw new Exception('Cannot load ' . $controller . '.php from ' . __DIR__ . '/../../../application/controllers/');
        }
    }

    protected function loadModel($model)
    {
        if (file_exists(__DIR__ . '/../../../application/models/' . $model . '.php')) {
            require_once __DIR__ . '/../../../application/models/' . $model . '.php';
        } else {
            throw new Exception('Cannot load' . $model . '.php from ' . __DIR__ . '/../../../application/models/');
        }
    }
}
