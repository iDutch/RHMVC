<?php

namespace RHMVC;

use MatthiasMullie\Minify;

class Helper {

    private static $instance    = null;

    private $javascripts    = array();
    private $stylesheets    = array();

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

    public function getJS()
    {
        $js = null;
        foreach ($this->javascripts as $types) {
            foreach ($types as $type => $value) {
                if($type === 'file'){
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
        $hash = md5(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        if (!file_exists(__DIR__ . '/../../../docs/static/cache/js/minified-' . $hash . '.js') || isset($_GET['debug'])) {
            $js = null;
            foreach ($this->javascripts as $types) {
                foreach ($types as $type => $value) {
                    if($type === 'file'){
                        $js .= file_get_contents(__DIR__ . '/../../../docs' . $value);
                    } else if ($type === 'url') {
                        $js .= file_get_contents($value);
                    } else {
                        $js .= $value;
                    }
                }
            }

            $minify = new Minify\JS($js);
            $minify->minify(__DIR__ . '/../../../docs/static/cache/js/minified-' . $hash . '.js');
        }

        return '<script src="/static/cache/js/minified.js"></script>';
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

    public function getCSSMinified()
    {
        $hash = md5(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        if (!file_exists(__DIR__ . '/../../../docs/static/cache/js/minified-' . $hash . '.css') || isset($_GET['debug'])) {
            $css = null;
            foreach ($this->stylesheets as $types) {
                foreach ($types as $type => $value) {
                    if($type === 'file'){
                        $css .= file_get_contents(__DIR__ . '/../../../docs' . $value);
                    } else if ($type === 'url') {
                        $css .= file_get_contents($value);
                    } else {
                        $css .= $value;
                    }
                }
            }

            $minify = new Minify\CSS($js);
            $minify->minify(__DIR__ . '/../../../docs/static/cache/css/-' . $hash . '.css');
        }

        return '<link rel="stylesheet" href="/static/cache/js/minified.css">';
    }



} 