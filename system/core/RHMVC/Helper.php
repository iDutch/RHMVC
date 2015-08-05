<?php
/**
 * Created by PhpStorm.
 * User: richard
 * Date: 7/31/15
 * Time: 1:16 PM
 */

class Helper {

    private static $javascripts = array();
    private static $stylesheets = array();

    public static function appendJSFile($file)
    {
        array_push(self::$javascripts, array('file' => $file));
    }

    public static function appendCSSFile($file)
    {
        array_push(self::$stylesheets, array('file' => $file));
    }

    public static function appendJSInline($content)
    {
        array_push(self::$javascripts, array('inline' => $content));
    }

    public static function appendCSSInline($content)
    {
        array_push(self::$stylesheets, array('inline' => $content));
    }

    public static function getJS()
    {
        $js = null;
        foreach (self::$javascripts as $types) {
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

    public static function getCSS()
    {
        $css = null;
        foreach (self::$stylesheets as $types) {
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

} 