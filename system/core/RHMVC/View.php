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
        if (!file_exists($view)) {
            throw new Exception('View error: Cannot load view: \'' . $view . '\'');
        }
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

    public function parseJS()
    {
        ob_start();
        require $this->view;
        $html = ob_get_clean();

        $js = '';
        //header("Content-Type: text/x-javascript; charset=utf-8");
        foreach (preg_split("/[\r\n]+/", $html) as $line) {
            if (substr(trim($line), 0, 2) !== "//") //We cannot document.write comments!!! This only strips out line starting with comments
            $js .= "document.write('" . addslashes(trim($line)) . "');\n";
        }
        return $js;
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
        require __DIR__ . '/../../../application/views/partials/' . $partial;
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