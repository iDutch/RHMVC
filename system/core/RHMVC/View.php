<?php

namespace RHMVC;

use RHMVC\Translator;
use RHMVC\Helper;

class View
{

    private $view;
    private $vars = array();

    public function __construct($view, $isLayout = false)
    {
        if ($isLayout) {
            if (!file_exists(LAYOUT_DIR . $view)) {
                throw new \Exception('View error: Cannot load layout: \'' . LAYOUT_DIR . $view . '\'');
            }
            $this->view = LAYOUT_DIR . $view;
        } else {
            if (!file_exists(VIEW_DIR . $view)) {
                throw new \Exception('View error: Cannot load view: \'' . VIEW_DIR . $view . '\'');
            }
            $this->view = VIEW_DIR . $view;
        }
    }

    public function setVars(array $vars = array())
    {
        foreach ($vars as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function render()
    {
        ob_start();
        require $this->view;
        return ob_get_flush();
    }

    public function parse()
    {
        ob_start();
        require $this->view;
        return ob_get_clean();
    }

    public function partial($partial, array $vars = array())
    {
        $this->setVars($vars);
        ob_start();
        require PARTIAL_DIR . $partial;
        return ob_get_clean();
    }

    function __set($key, $value){
        $this->vars[$key] = $value; //create new set data[key] = value without setters;
    }

    function __get($key){
        return $this->vars[$key];
    }

    public function helper()
    {
        return Helper::getInstance();
    }

    public function translate($key)
    {
        return Translator::getInstance($_SESSION['language_iso_code'])->translate($key);
    }

} 