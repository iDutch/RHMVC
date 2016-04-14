<?php

class CategoryController extends AbstractController
{

    public function admin_indexAction()
    {
        $CategoryModel = $this->loadModel('CategoryModel');

        $view = new View(__DIR__ . '/../../application/views/category/admin_index.phtml');
        $view->setVars(array(
            'categories' => $CategoryModel->getAll(),
            'messages' => $this->flashMessage()->display(null, false),
        ));

        return $view->parse();
    }

    public function admin_addAction()
    {
        $CategoryModel = $this->loadModel('CategoryModel');

        if (count($_POST) > 0) {
            $CategoryModel->saveCategory($_POST);
            $this->flashMessage()->success('category added', '/admin/category/list');
        }

        $view = new View(__DIR__ . '/../../application/views/category/admin_add.phtml');
        $view->setVars(array(
            'languages'     => $this->getLanguages(),
        ));

        return $view->parse();
    }

    public function admin_editAction($id)
    {
        $CategoryModel = $this->loadModel('CategoryModel');

        if (count($_POST) > 0) {
            $CategoryModel->saveCategory($_POST, $id);
            $this->flashMessage()->success('category updated', '/admin/category/list');
        }

        $view = new View(__DIR__ . '/../../application/views/category/admin_edit.phtml');
        $view->setVars(array(
            'languages'     => $this->getLanguages(),
            'category'     => $CategoryModel->getSingle($id),
        ));
        
        return $view->parse();
    }

}