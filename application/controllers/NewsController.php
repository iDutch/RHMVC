<?php

class NewsController extends AbstractController
{

    public function __construct()
    {
        $this->loadModel('NewsModel');
    }

    public function indexAction()
    {
        $NewsModel = new NewsModel();

        return $view->parse();
    }

}
