<?php
/**
 * Created by PhpStorm.
 * User: richard
 * Date: 7/31/15
 * Time: 1:16 PM
 */

class Helper {

    private static $instance    = null;

    private $javascripts = array();
    private $stylesheets = array();

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {

    }

    public function appendJSFile($file)
    {
        array_push($this->javascripts, array('file' => $file));
    }

    public function appendCSSFile($file)
    {
        array_push($this->stylesheets, array('file' => $file));
    }

    public function appendJSInline($content)
    {
        array_push($this->javascripts, array('inline' => $content));
    }

    public function appendCSSInline($content)
    {
        array_push($this->stylesheets, array('inline' => $content));
    }

    public function getJS()
    {
        $js = null;
        foreach ($this->javascripts as $types) {
            foreach ($types as $type => $value) {
                if($type === 'file'){
                    $js .= "<script type=\"text/javascript\" src=\"" . $value . "\"></script>\n\t";
                } else {
                    $js .= "<script type=\"text/javascript\">" . $value . "</script>\n\t";
                }
            }
        }
        return $js;
    }

    public function getCSS()
    {
        $css = null;
        foreach ($this->stylesheets as $types) {
            foreach ($types as $type => $value) {
                if($type === 'file'){
                    $css .= "<link rel=\"stylesheet\" href=\"" . $value . "\">\n\t";
                } else {
                    $css .= "<style>" . $value . "</style>\n\t";
                }
            }
        }
        return $css;
    }

    public function translate($key)
    {
        $langdir = __DIR__ . '/../../../application/languages';
        foreach () {

        }
    }

} 