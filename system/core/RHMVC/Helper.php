<?php

namespace core\RHMVC;

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

    public function prependCSSFile($file)
    {
        array_unshift($this->stylesheets, array('file' => $file));
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

    public function getJS()
    {
        $js = null;
        foreach ($this->javascripts as $types) {
            foreach ($types as $type => $value) {
                if ($type === 'file') {
                    $js .= "<script type=\"text/javascript\" src=\"" . $value . "\"></script>\n\t";
                } else if ($type === 'url') {
                    $js .= "<script type=\"text/javascript\" src=\"" . $value . "\"></script>\n\t";
                } else {
                    $js .= "<script type=\"text/javascript\">" . $value . "</script>\n\t";
                }
            }
        }
        return $js;
    }

    public function getJSMinified()
    {
        $js = null;
        foreach ($this->javascripts as $types) {
            foreach ($types as $type => $value) {
                if ($type === 'file') {
                    $js .= file_get_contents(__DIR__ . '/../../../docs' . $value);
                } else if ($type === 'url') {
                    $js .= file_get_contents($value);
                } else {
                    $js .= $value;
                }
            }
        }
        $hash = md5($js);
        if (!file_exists(__DIR__ . '/../../../htdocs/static/cache/js/minified-' . $hash . '.js') || isset($_GET['debug'])) {
            $minify = new Minify\JS($js);
            $minify->minify(__DIR__ . '/../../../htdocs/static/cache/js/minified-' . $hash . '.js');
        }

        return '<script src="/static/cache/js/minified-' . $hash . '.js"></script>';
    }

    public function getCSS()
    {
        $css = null;
        foreach ($this->stylesheets as $types) {
            foreach ($types as $type => $value) {
                if ($type === 'file') {
                    $css .= "<link rel=\"stylesheet\" href=\"" . $value . "\">\n\t";
                } else {
                    $css .= "<style>" . $value . "</style>\n\t";
                }
            }
        }
        return $css;
    }

    public function getCSSMinified()
    {
        $css = null;
        foreach ($this->stylesheets as $types) {
            foreach ($types as $type => $value) {
                if ($type === 'file') {
                    $css .= file_get_contents(__DIR__ . '/../../../docs' . $value);
                } else if ($type === 'url') {
                    $css .= file_get_contents($value);
                } else {
                    $css .= $value;
                }
            }
        }
        $hash = md5($css);
        if (!file_exists(__DIR__ . '/../../../htdocs/static/cache/css/minified-' . $hash . '.css') || isset($_GET['debug'])) {
            $options = array('compress' => true);
            $parser = new \Less_Parser($options);
            $parser->parse($css);
            $css = $parser->getCss();

            $minify = new Minify\CSS($css);
            $minify->minify(__DIR__ . '/../../../htdocs/static/cache/css/minified-' . $hash . '.css');
        }

        return '<link rel="stylesheet" href="/static/cache/css/minified-' . $hash . '.css">';
    }

}
