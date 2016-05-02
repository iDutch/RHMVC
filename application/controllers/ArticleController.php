<?php

class ArticleController extends AbstractController
{

    use ACLTrait;

    public function admin_indexAction()
    {
        $ArticleModel = $this->loadModel('ArticleModel');

        $view = new View(__DIR__ . '/../../application/views/article/admin_index.phtml');
        $view->setVars(array(
            'articles' => $ArticleModel->getAll(),
            'messages' => $this->flashMessage()->display(null, false),
            'permissions' => array(
                'create' => $this->hasAccess('create'),
                'update' => $this->hasAccess('update'),
                'delete' => $this->hasAccess('delete'),
            ),
        ));

        return $view->parse();
    }

    public function admin_addAction()
    {
        $ArticleModel = $this->loadModel('ArticleModel');
        $CategoryModel = $this->loadModel('CategoryModel');

        if (count($_POST) > 0) {
            $ArticleModel->saveArticle($_POST);
            $this->flashMessage()->success('article added', null, '/admin/article/list');
        }

        $view = new View(__DIR__ . '/../../application/views/article/admin_add.phtml');
        $view->setVars(array(
            'languages'     => $this->getLanguages(),
            'categories'    => $CategoryModel->getList(),
        ));

        return $view->parse();
    }

    public function admin_editAction($id)
    {
        $ArticleModel = $this->loadModel('ArticleModel');
        $CategoryModel = $this->loadModel('CategoryModel');

        if (count($_POST) > 0) {
            $ArticleModel->saveArticle($_POST, $id);
            $this->flashMessage()->success('article updated', null, '/admin/article/list');
        }

        $view = new View(__DIR__ . '/../../application/views/article/admin_edit.phtml');
        $view->setVars(array(
            'languages'     => $this->getLanguages(),
            'article'     => $ArticleModel->getSingle($id),
            'categories'    => $CategoryModel->getList(),
        ));
        
        return $view->parse();
    }

}
