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
    private $javascripts = array();
    private $stylesheets = array();

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

    public function appendJSFile($file)
    {
        $this->javascripts[]['file'] = $file;
    }

    public function appendCSSFile($file)
    {
        $this->stylesheets[]['file'] = $file;
    }

    public function appendJSInline($content)
    {
        $this->javascripts[]['inline'] = $content;
    }

    public function appendCSSInline($content)
    {
        $this->stylesheets[]['inline'] = $content;
    }

    public function getJS()
    {
        $js = null;
        foreach ($this->javascripts as $types) {
            foreach ($types as $type => $value) {
                if($type === 'file'){
                    $js .= '<script type="text/javascript" src="'.$value.'"></script>';
                } else {
                    $js .= '<script type="text/javascript">' . $value . '</script>';
                }
            }
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