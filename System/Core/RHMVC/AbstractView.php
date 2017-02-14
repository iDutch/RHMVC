<?php

namespace System\Core\RHMVC;


use System\Libs\Messenger\Messenger;

abstract class AbstractView
{

    protected $_helper;
    protected $_translator;
    protected $_messenger;

    protected function __construct(Helper $helper, Translator $translator, Messenger $messenger)
    {
        $this->_helper = $helper;
        $this->_translator = $translator;
        $this->_messenger = $messenger;
    }
}