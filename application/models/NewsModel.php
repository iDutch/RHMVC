<?php

class NewsModel extends AbstractModel
{

    public function __construct()
    {
        $this->loadModelConfig(__CLASS__);
    }

    public function getLatest()
    {
        DBAdapter::getInstance()->select();
    }

}