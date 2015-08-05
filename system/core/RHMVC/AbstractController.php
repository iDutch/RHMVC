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
        require_once __DIR__ . '/../../../application/controllers/' . $controller . '.php';
        $controller = new $controller($this->layout);
        return call_user_func_array(array($controller, $action), $params);
    }

    protected function loadModel($model)
    {
        require_once __DIR__ . '/../../../application/models/' . $model . '.php';
    }
} 