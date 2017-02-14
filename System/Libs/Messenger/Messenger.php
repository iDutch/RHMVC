<?php

namespace System\Libs\Messenger;

class Messenger
{

    const INFO      = 1;
    const SUCCESS   = 2;
    const WARNING   = 4;
    const ERROR     = 8;

    private $css_classes = [
        1 => 'alert-info',
        2 => 'alert-success',
        4 => 'alert-warning',
        8 => 'alert-danger'
    ];

    private $messages = [];

    public function __construct(){}

    /**
     * Set info message
     *
     * @param $field
     * @param $message
     */
    public function info($field, $message)
    {
        $this->add($field, $message, self::INFO);
    }

    /**
     * Set success message
     *
     * @param $field
     * @param $message
     */
    public function success($field, $message)
    {
        $this->add($field, $message, self::SUCCESS);
    }

    /**
     * Set danger message
     *
     * @param $field
     * @param $message
     */
    public function warning($field, $message)
    {
        $this->add($field, $message, self::WARNING);
    }

    /**
     * Set error message
     *
     * @param $field
     * @param $message
     */
    public function error($field, $message)
    {
        $this->add($field, $message, self::ERROR);
    }

    /**
     * Set message
     *
     * @param $field
     * @param $message
     * @param int $type
     */
    private function add($field, $message, $type = self::INFO)
    {
        $this->messages[$field][$type] = $message;
    }

    /**
     * Display messages unfiltered by default. Filtering can be done on field and/or type
     *
     * @param null $field
     * @param null $typefilter
     * @param string $html
     * @return string
     */
    public function display($html = '%s', $field = null, $typefilter = null)
    {
        $messagestring = '';
        foreach ($this->messages as $fieldname => $types) {
            if (!is_null($field) && $field != $fieldname) continue;
            foreach ($types as $k => $message) {
                if (!is_null($typefilter) && intval($typefilter & $k) == 0) continue;
                if (is_array($message)) {
                    $messages = $message;
                    foreach ($messages as $message) {
                        $messagestring .= sprintf($html, $message, $this->css_classes[$k]);
                    }
                } else {
                    $messagestring .= sprintf($html, $message, $this->css_classes[$k]);
                }
            }
        }

        return $messagestring;
    }

}