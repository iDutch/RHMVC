<?php

abstract class HMVC
{

    protected $layout = null;

    protected function invokeController($controller, $action, array $params = array())
    {
        $controller_file = __DIR__ . '/../../../application/controllers/' . $controller . '.php';

        if (!file_exists($controller_file)) {
            throw new Exception('HMVC error: Cannot invoke controller: \'' . $controller_file . '\'');
        }

        require_once $controller_file;
        $controller = new $controller($this->layout);

        if (!method_exists($controller, $action)){
            throw new Exception('HMVC error: ' . $controller . ' :: ' . $action . ' does not exist!');
        }

        return call_user_func_array(array($controller, $action), $params);
    }

    protected function loadModel($model)
    {
        $model_file = __DIR__ . '/../../../application/models/' . $model . '.php';

        if (!file_exists($model_file)) {
            throw new Exception('HMVC error: Cannot load model: \'' . $model_file . '\'');
        }
        require_once $model_file;

        return new $model();
    }

    protected function flashMessage()
    {
        return new \Plasticbrain\FlashMessages\FlashMessages();
    }

}
