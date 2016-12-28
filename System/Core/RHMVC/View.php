<?php

namespace System\Core\RHMVC;

use Exception;
use System\Libs\Messages\Messages;
use System\Core\RHMVC\Router;

class View
{

    private $_view;
    private $_vars = array();
    private $_helper;
    private $_messages;
    private $_router;

    /**
     * View constructor.
     * @param $view
     * @param bool $isLayout
     * @throws Exception
     */
    public function __construct($view, $isLayout = false)
    {
        $this->_helper = Helper::getInstance($view);
        $this->_messages = Messages::getInstance();
        $this->_router = new Router();
        if ($isLayout) {
            if (!file_exists(LAYOUT_DIR . $view)) {
                throw new Exception('View error: Cannot load layout: \'' . LAYOUT_DIR . $view . '\'');
            }
            $this->_view = LAYOUT_DIR . $view;
        } else {
            if (!file_exists(VIEW_DIR . $view)) {
                throw new Exception('View error: Cannot load view: \'' . VIEW_DIR . $view . '\'');
            }
            $this->_view = VIEW_DIR . $view;
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
        require $this->_view;
        return ob_get_flush();
    }

    public function parse()
    {
        ob_start();
        require $this->_view;
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
     * @param $key
     * @param $value
     */
    public function __set($key, $value)
    {
        $this->_vars[$key] = $value; //create new set data[key] = value without setters;
    }

    /**
     * Magic getter
     * @param $key
     * @return Mixed
     */
    public function __get($key)
    {
        return $this->_vars[$key];
    }

    /**
     * Translator function
     * @param $key
     * @return Translator
     */
    public function translate($key)
    {
        return Translator::getInstance('en')->translate($key);
    }

}
