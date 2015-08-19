<?php
/**
 * Created by PhpStorm.
 * User: richard
 * Date: 7/28/15
 * Time: 9:59 AM
 */

class Dispatcher
{
    private $layout;

    private function loadLayout($layout)
    {
        if (file_exists(__DIR__ . '/../../../application/layout/' .$layout)) {
            $this->layout = new View(__DIR__ . '/../../../application/layout/' .$layout);
        } else {
            throw new Exception('File: \'' . __DIR__ . '/../../../application/layout/' . $layout .'\' does not exists!');
        }
    }

    public function dispatch($route)
    {
        $this->loadLayout($route['layout']);
        $template_vars = array();
        foreach ($route as $segment => $controllers) {
            if (is_array($controllers)) {
                $template_vars[$segment] = null;
                foreach ($controllers as $k => $controller) {
                    $template_vars[$segment] .= $this->invokeController($controller);
                }
            }
        }
        $this->layout->setVars($template_vars);

        return $this->layout->render();
    }

    private function invokeController(array $controller_info)
    {
        require_once __DIR__ . '/../../../application/controllers/' . $controller_info['controller'] . '.php';
        $controller = new $controller_info['controller']($this->layout);
        return call_user_func_array(array($controller, $controller_info['action']), $controller_info['params']);
    }



} 