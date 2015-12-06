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

        $view = new View(__DIR__ . '/../../application/views/news/list.phtml');
        $view->setVars(array(
            'news_items' => $NewsModel->getLatest(),
        ));

        return $view->parse();
    }

}
