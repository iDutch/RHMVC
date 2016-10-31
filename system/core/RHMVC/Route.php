<?php

namespace core\RHMVC;

use core\RHMVC\View;

class Route {

    private $layout;
    private $routedata;
    private $routeparams;

    public function __construct($routedata, array $routeparams = []) {
        $this->routedata = $routedata;
        $this->routeparams = $routeparams;
    }

    private function loadLayout($layout) {
        $this->layout = new View($layout, true);
    }

    public function dispatch() {
        $template_vars = array();
        //Loop through route info
        foreach ($this->routedata as $segment => $controllers) {
            if (is_array($controllers)) {
                $template_vars[$segment] = null;
                //Loop through controllers for each segment
                foreach ($controllers as $k => $controller) {
                    foreach ($this->routeparams as $key => $value) {
                        if (array_key_exists($key, $this->routedata[$segment][$k]['params']) && !empty($value)) {
                            $controller['params'][$key] = $value;
                        }
                    }
                    //Append content returned from controller to segment
                    $template_vars[$segment] .= $this->invokeController($controller);
                }
            }
        }
        if (!is_null($this->routedata['layout'])) {
            $this->loadLayout($this->routedata['layout']);
            $this->layout->setVars($template_vars);

            return $this->layout->render();
        }

        return $template_vars['content']; //Return when layout is set to null. Recommended usage for AJAX calls and JSON reponses
    }

    private function invokeController(array $controller_info) {
        $controller_file = CONTROLLER_DIR . $controller_info['controller'] . '.php';

        //Does the controller exists?
        if (!file_exists($controller_file)) {
            throw new \Exception('Dispatcher error: Cannot invoke controller: \'' . $controller_file . '\'');
        }
        require_once $controller_file;
        $controller = new $controller_info['controller']($this->layout);

        //Does the action exists?
        if (!method_exists($controller, $controller_info['action'])) {
            throw new \Exception('Dispatcher error: ' . $controller_info['controller'] . '::' . $controller_info['action'] . ' not found!');
        }

        //Does it returns a string? If not, it can't be appended to a layout segment so we throw an Exception
        $string = call_user_func_array(array($controller, $controller_info['action']), $controller_info['params']);
        if (!is_string($string)) {
            throw new \Exception('Dispatcher error: ' . $controller_info['controller'] . '::' . $controller_info['action'] . ' does not return a string! ' . ucfirst(gettype($string)) . ' returned instead!');
        }
        return $string;
    }

}
