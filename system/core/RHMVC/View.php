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

    public function renderJS()
    {
        ob_start();
        require $this->view;
        $html = ob_get_clean();

        header("Content-Type: text/x-javascript; charset=utf-8");
        foreach (preg_split("/[\r\n]+/", $html) as $line) {
            printf("document.write('%s');\n", addslashes(trim($line)));
        }
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