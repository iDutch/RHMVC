<?php

namespace System\Core\RHMVC;

use Exception;

class Route
{

    private $layout;
    private $routedata;
    private $routeparams;

    public function __construct($routedata, array $routeparams = [])
    {
        $this->routedata = $routedata;
        $this->routeparams = $routeparams;
    }

    private function loadLayout($layout)
    {
        $this->layout = new View($layout, true);
    }

    public function dispatch()
    {
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

    private function invokeController(array $controller_info)
    {
        $controller = 'Application\\Controllers\\' . $controller_info['controller'];

        if (!class_exists($controller)) {
            throw new Exception('Route error: Controller \'' . $controller . '\' not found!');
        }

        $controller = new $controller($this->layout);

        //Does the action exists?
        if (!method_exists($controller, $controller_info['action'])) {
            throw new Exception('Route error: ' . $controller_info['controller'] . '::' . $controller_info['action'] . ' not found!');
        }

        //Does it returns a string? If not, it can't be appended to a layout segment so we throw an Exception
        $string = call_user_func_array(array($controller, $controller_info['action']), $controller_info['params']);
        if (!is_string($string)) {
            throw new Exception('Route error: ' . $controller_info['controller'] . '::' . $controller_info['action'] . ' does not return a string! ' . ucfirst(gettype($string)) . ' returned instead!');
        }
        return $string;
    }

    public function getURL(array $params = [])
    {
        $route = (isset($_SESSION['language']) ? '/' . strtolower($_SESSION['language']['iso_code']) : '') . preg_replace('#\\(\\?\\<([\\w-]+)\\>(\\[[\\w-\\\\]+\\]|\\([\\w|]+\\))(\\+|{[0-9,]+})?\\)(\\?)?#', '$1', $this->routedata['route']);
        return str_replace(array_keys($params), array_values($params), $route);
    }

}
