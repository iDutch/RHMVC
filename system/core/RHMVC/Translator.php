<?php

class Translator {

    private static $instance = array();

    private $translations = array();

    public static function getInstance($language_iso_code)
    {
        if (!isset(self::$instance[$language_iso_code])) {
            self::$instance[$language_iso_code] = new self($language_iso_code);
        }
        return self::$instance[$language_iso_code];
    }

    public function __construct($language_iso_code)
    {
        $this->loadTranslations($language_iso_code);
    }

    private function loadTranslations($language_iso_code)
    {
        $langdir = __DIR__ . '/../../../application/languages';
        foreach (scandir($langdir) as $file) {
            if (preg_match('/.' . $language_iso_code . '.php$/', $file)) {
                $module_translations = require $langdir . '/' . $file;
                $this->translations = array_merge($this->translations, $module_translations);
            }
        }
    }

    public function translate($key)
    {
        if (array_key_exists($key, $this->translations)) {
            return $this->translations[$key];
        }
        return '[' . $key . ']';
    }

} 