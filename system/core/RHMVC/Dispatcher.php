<?php

class Dispatcher
{
    private $layout;

    private function loadLayout($layout)
    {
        $layout_file = __DIR__ . '/../../../application/layout/' . $layout;
        if (file_exists($layout_file)) {
            $this->layout = new View($layout_file);
        } else {
            throw new Exception('Dispatcher error: File: \'' . $layout_file . '\' does not exists!');
        }
    }

    public function dispatch($route)
    {
        $template_vars = array();
        //Loop through route info
        foreach ($route as $segment => $controllers) {
            if (is_array($controllers)) {
                $template_vars[$segment] = null;
                //Loop through controllers for each segment
                foreach ($controllers as $k => $controller) {
                    //Append content returned from controller to segment
                    $template_vars[$segment] .= $this->invokeController($controller);
                }
            }
        }
        if (!is_null($route['layout'])) {
            $this->loadLayout($route['layout']);
            $this->layout->setVars($template_vars);

            return $this->layout->render();
        }

        return $template_vars['content'];
    }

    private function invokeController(array $controller_info)
    {
        $controller_file = __DIR__ . '/../../../application/controllers/' . $controller_info['controller'] . '.php';

        //Does the controller exists?
        if (!file_exists($controller_file)) {
            throw new Exception('Dispatcher error: Cannot invoke controller: \'' . $controller_file . '\'');
        }
        require_once $controller_file;
        $controller = new $controller_info['controller']($this->layout);

        //Does the action exists?
        if (!method_exists($controller, $controller_info['action'])) {
            throw new Exception('Dispatcher error: ' . $controller_info['controller'] . '::' . $controller_info['action'] . ' not found!');
        }

        //Does it returns a string? If not, it can't be appended to a layout segment so we throw an Exception
        $string = call_user_func_array(array($controller, $controller_info['action']), $controller_info['params']);
        if (!is_string($string)) {
            throw new Exception('Dispatcher error: ' . $controller_info['controller'] . '::' . $controller_info['action'] . ' does not return a string! ' . ucfirst(gettype($string)) . ' returned instead!');
        }
        return $string;
    }
}
