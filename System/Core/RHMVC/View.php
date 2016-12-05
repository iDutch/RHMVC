<?php

namespace System\Core\RHMVC;

use System\Core\RHMVC\Translator;
use System\Core\RHMVC\Helper;
use Exception;

class View
{

    private $view;
    private $vars = array();
    private $helper;

    /**
     * Constructor
     * @param type $view
     * @param type $isLayout
     * @throws \Exception
     */
    public function __construct($view, $isLayout = false)
    {
        $this->helper = Helper::getInstance($view);
        if ($isLayout) {
            if (!file_exists(LAYOUT_DIR . $view)) {
                throw new Exception('View error: Cannot load layout: \'' . LAYOUT_DIR . $view . '\'');
            }
            $this->view = LAYOUT_DIR . $view;
        } else {
            if (!file_exists(VIEW_DIR . $view)) {
                throw new Exception('View error: Cannot load view: \'' . VIEW_DIR . $view . '\'');
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

    /**
     * Magic setter
     * @param type $key
     * @param type $value
     */
    public function __set($key, $value)
    {
        $this->vars[$key] = $value; //create new set data[key] = value without setters;
    }

    /**
     * Magic getter
     * @param type $key
     * @return type Mixed
     */
    public function __get($key)
    {
        return $this->vars[$key];
    }

    /**
     * Translator function
     * @param type $key
     * @return type
     */
    public function translate($key)
    {
        return Translator::getInstance('en')->translate($key);
    }

}
