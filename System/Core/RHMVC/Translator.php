<?php

namespace System\Core\RHMVC;

class Translator
{

    private $translations = [];

    public function __construct()
    {
        $this->loadTranslations();
    }

    private function loadTranslations()
    {
        $language_iso_code = isset($_SESSION['language']['iso_code']) ? $_SESSION['language']['iso_code'] : 'en';
        $langdir = __DIR__ . '/../../../Application/Languages';

        foreach (scandir($langdir) as $file) {
            if (preg_match('/.' . strtolower($language_iso_code). '.php$/', $file)) {
                $module_translations = require $langdir . '/' . $file;
                $this->translations = array_merge($this->translations, $module_translations);
            }
        }
    }

    private function sprintf_array($format, array $array)
    {
        return call_user_func_array('sprintf', array_merge((array) $format, $array));
    }

    public function translate($key, array $array = [])
    {
        if (array_key_exists($key, $this->translations)) {
            return $this->sprintf_array($this->translations[$key], $array);
        }
        return '[' . $key . ']';
    }

}
