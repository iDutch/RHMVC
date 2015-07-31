<?php
/**
 * Created by PhpStorm.
 * User: richard
 * Date: 7/28/15
 * Time: 11:17 AM
 */

class View
{

    private $view;
    private $vars = array();

    public function __construct($view)
    {
        $this->view = $view;
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

    function __set($key, $value){
        $this->vars[$key] = $value; //create new set data[key] = value without setters;
    }

    function __get($key){
        return $this->vars[$key];
    }

} 