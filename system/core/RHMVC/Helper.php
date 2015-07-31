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
        self::$stylesheets[]['file'] = $file;
    }

    public static function appendJSInline($content)
    {
        array_push(self::$javascripts, array('inline' => $content));
    }

    public static function appendCSSInline($content)
    {
        self::$stylesheets[]['inline'] = $content;
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

} 