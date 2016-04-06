<?php

class NewsController extends AbstractController
{

    public function __construct()
    {
        $this->loadModel('NewsModel');
    }

    public function admin_indexAction()
    {
        $NewsModel = new NewsModel();

        $view = new View(__DIR__ . '/../../application/views/news/admin_index.phtml');
        $view->setVars(array(
            'news_items' => $NewsModel->getAll(),
        ));

        return $view->parse();
    }

    public function editAction($id)
    {
        $this->loadModel('CategoryModel');

        $NewsModel      = new NewsModel();
        $CategoryModel  = new CategoryModel();
        $view = new View(__DIR__ . '/../../application/views/news/edit.phtml');
        $view->setVars(array(
            'languages'     => $this->getLanguages(),
            'news_item'     => $NewsModel->getSingle($id),
            'categories'    => $CategoryModel->getList(),
        ));

        return $view->parse();
    }

}
