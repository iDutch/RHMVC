<?php
/**
 * Created by PhpStorm.
 * User: Digitaal Kantoor
 * Date: 30-1-2017
 * Time: 16:02
 */

namespace System\Core\RHMVC;


abstract class AbstractView extends Core
{

    protected $_helper;

    protected function __construct($helper)
    {
        $this->_helper = $helper;
    }
}