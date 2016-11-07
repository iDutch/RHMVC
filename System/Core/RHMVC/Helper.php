<?php

namespace System\Core\RHMVC;

use MatthiasMullie\Minify;

class Helper
{

    private static $instance = null;
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

    public function appendJSUrl($url)
    {
        array_push($this->javascripts, array('url' => $url));
    }

    public function appendJSInline($content)
    {
        array_push($this->javascripts, array('inline' => $content));
    }

    public function appendCSSInline($content)
    {
        array_push($this->stylesheets, array('inline' => $content));
    }

    public function getJSFiles()
    {
        $js = null;
        $javascripts = array_reverse($this->javascripts);
        foreach ($javascripts as $types) {
            foreach ($types as $type => $value) {
                if ($type === 'file') {
                    $js .= '<script src="/static/js/' . $value . '"></script>' . "\n";
                }
            }
        }

        return $js;
    }

    public function getJS($debug = false)
    {
        $js = null;
        $javascripts = array_reverse($this->javascripts);
        foreach ($javascripts as $types) {
            foreach ($types as $type => $value) {
                if ($type === 'url') {
                    $js .= file_get_contents($value);
                } else if ($type === 'inline') {
                    $js .= $value;
                }
            }
        }
        $hash = md5($js);
        if (!file_exists(JS_DIR . 'cache/minified-' . $hash . '.js') || $debug) {
            $minify = new Minify\JS($js);
            $minify->minify(JS_DIR . 'cache/minified-' . $hash . '.js');
        }

        if ($debug) {
            return "<script>\n" . $js . "</script>\n";
        }
        return '<script src="/static/js/cache/minified-' . $hash . '.js"></script>' . "\n";
    }

    public function getCSS($debug = false)
    {
        $css = null;
        $stylesheets = array_reverse($this->stylesheets);
        foreach ($stylesheets as $types) {
            foreach ($types as $type => $value) {
                if ($type === 'file') {
                    $css .= file_get_contents(CSS_DIR . $value);
                } else if ($type === 'url') {
                    $css .= file_get_contents($value);
                } else {
                    $css .= $value;
                }
            }
        }
        $hash = md5($css);
        if (!file_exists(CSS_DIR . 'cache/minified-' . $hash . '.css') || $debug) {
            $options = array('compress' => true);
            $parser = new \Less_Parser($options);
            $parser->parse($css);
            $css = $parser->getCss();

            $minify = new Minify\CSS($css);
            $minify->minify(CSS_DIR . 'cache/minified-' . $hash . '.css');
        }

        if ($debug) {
            return "<style>\n" . $css . "</css>\n";
        }
        return '<link type="text/css" rel="stylesheet" href="/static/css/cache/minified-' . $hash . '.css">';
    }

    function getClassProperty($object, $accessString)
    {
        $parts = explode('->', $accessString);

        $tmp = $object;
        foreach ($parts as $part) {
            if (preg_match('/\[(?<index>[0-9]+)\]/i', $part, $matches)) { //Property is an array?
                $tmp = $tmp->{preg_replace('/\[([0-9]+)\]/', '', $part)}[$matches['index']];
            } else {
                $tmp = $tmp->{$part};
            }
        }
        return $tmp;
    }

}
