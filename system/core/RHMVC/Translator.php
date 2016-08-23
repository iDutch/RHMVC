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
        $translationfile = __DIR__ . '/../../../application/languages/translations.' . $language_iso_code . '.php';
        $this->translations = require $translationfile;
    }

    private function sprintf_array($format, array $array)
    {
        return call_user_func_array('sprintf', array_merge((array)$format, $array));
    }

    public function translate($key, array $array = array())
    {
        if (array_key_exists($key, $this->translations)) {
            return $this->sprintf_array($this->translations[$key], $array);
        }

        DBAdapter::getInstance()->query('
            INSERT INTO translation (keyword)
            VALUES (\'' . $key . '\')
            ON DUPLICATE KEY UPDATE keyword=keyword;
        ');
        //INSERT INTO table_tags (tag) VALUES ('tag_a'),('tab_b'),('tag_c') ON DUPLICATE KEY UPDATE tag=tag;
        return '[' . $key . ']';
    }

} 