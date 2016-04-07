<?php

class ArticleController extends AbstractController
{

    public function admin_indexAction()
    {
        $ArticleModel = $this->loadModel('ArticleModel');

        $view = new View(__DIR__ . '/../../application/views/news/admin_index.phtml');
        $view->setVars(array(
            'articles' => $ArticleModel->getAll(),
        ));

        return $view->parse();
    }

    public function admin_editAction($id)
    {
        $ArticleModel = $this->loadModel('ArticleModel');
        $CategoryModel = $this->loadModel('CategoryModel');

        if (count($_POST) > 0) {
            $ArticleModel->saveArticle($_POST);
        }

        $view = new View(__DIR__ . '/../../application/views/news/admin_edit.phtml');
        $view->setVars(array(
            'languages'     => $this->getLanguages(),
            'article'     => $ArticleModel->getSingle($id),
            'categories'    => $CategoryModel->getList(),
        ));
        
        return $view->parse();
    }

}
