<?php

namespace System\Core\RHMVC;

use MatthiasMullie\Minify;

class Helper
{

    private static $instance = null;
    private $javascripts = [];
    private $stylesheets = [];
    private $view;

    public static function getInstance($view)
    {
        if (!isset(self::$instance)) {
            self::$instance = new self($view);
        }
        return self::$instance;
    }

    public function __construct($view)
    {
        $this->view = $view;
    }

    public function appendJSFile($file)
    {
        if (!isset($this->javascripts[$this->view])) {
            $this->javascripts[$this->view] = [];
        }
        array_push($this->javascripts[$this->view], array('file' => $file));
    }

    public function appendCSSFile($file)
    {
        if (!isset($this->stylesheets[$this->view])) {
            $this->stylesheets[$this->view] = [];
        }
        array_push($this->stylesheets[$this->view], array('file' => $file));
    }

    public function appendJSUrl($url)
    {
        if (!isset($this->javascripts[$this->view])) {
            $this->javascripts[$this->view] = [];
        }
        array_push($this->javascripts[$this->view], array('url' => $url));
    }

    public function appendJSInline($content)
    {
        if (!isset($this->javascripts[$this->view])) {
            $this->javascripts[$this->view] = [];
        }
        array_push($this->javascripts[$this->view], array('inline' => $content));
    }

    public function appendCSSInline($content)
    {
        if (!isset($this->stylesheets[$this->view])) {
            $this->stylesheets[$this->view] = [];
        }
        array_push($this->stylesheets[$this->view], array('inline' => $content));
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

        foreach ($javascripts as $views => $entries) {
            foreach ($entries as $index => $jsfiles) {
                foreach ($jsfiles as $type => $data) {
                    if ($type === 'file') {
                        $js .= file_get_contents(JS_DIR . $data);
                    } else if ($type === 'url') {
                        $js .= file_get_contents($data);
                    } else if ($type === 'inline') {
                        $js .= $data;
                    }
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

        foreach ($stylesheets as $views => $entries) {
            foreach ($entries as $index => $cssfiles) {
                foreach ($cssfiles as $type => $data) {
                    if ($type === 'file') {
                        $css .= file_get_contents(CSS_DIR . $data);
                    } else if ($type === 'url') {
                        $css .= file_get_contents($data);
                    } else {
                        $css .= $data;
                    }
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

    public function getClassProperty($obj, $propertystring)
    {
        $properties = explode('->', $propertystring);
        $tmp = $obj;
        foreach ($properties as $property) {
            if (preg_match('/\[(?<index>[0-9]+)\]/', $property, $matches)) { //Property is array
                $property = preg_replace('/\[[0-9]+\]/', '', $property);
                $tmp = $tmp->{$property}[$matches['index']];
            } else {
                $tmp = $tmp->{$property};
            }
        }
        return $tmp;
    }

}
