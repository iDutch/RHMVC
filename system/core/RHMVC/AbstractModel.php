<?php

abstract class AbstractModel
{

    public function __construct()
    {

    }

    /*protected function loadLibrary($libdir)
    {
       if (is_dir(__DIR__ . '/../../library/' . $libdir . '/classes')) {
            foreach (scandir(__DIR__ . '/../../library/' . $libdir . '/classes') as $item) {
                if ($item !== '.' && $item !== '..') {
                    require_once __DIR__ . '/../../library/' . $libdir . '/classes/'. $item;
                }
            }
        }
    }*/
} 