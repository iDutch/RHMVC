<?php

class CategoryController extends AbstractController
{

    use ACLTrait;

    public function admin_readAction()
    {
        /**
         * @var $CategoryModel CategoryModel
         */

        if (!$this->hasAccess(__FUNCTION__)) {
            header("Location: /admin/dashboard");
        }

        $CategoryModel = $this->loadModel('CategoryModel');

        $view = new View(__DIR__ . '/../../application/views/category/admin_index.phtml');
        $view->setVars(array(
            'categories' => $CategoryModel->getAll(),
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
        /**
         * @var $CategoryModel CategoryModel
         */

        if (!$this->hasAccess(__FUNCTION__)) {
            header("Location: /admin/dashboard");
        }

        $CategoryModel = $this->loadModel('CategoryModel');

        if (count($_POST) > 0) {
            $CategoryModel->saveCategory($_POST);
            $this->flashMessage()->success('category added', null, '/admin/category/list');
        }

        $view = new View(__DIR__ . '/../../application/views/category/admin_add.phtml');
        $view->setVars(array(
            'languages'     => $this->getLanguages(),
        ));

        return $view->parse();
    }

    public function admin_editAction($id)
    {
        /**
         * @var $CategoryModel CategoryModel
         */

        if (!$this->hasAccess(__FUNCTION__)) {
            header("Location: /admin/dashboard");
        }

        $CategoryModel = $this->loadModel('CategoryModel');

        if (count($_POST) > 0) {
            $CategoryModel->saveCategory($_POST, $id);
            $this->flashMessage()->success('category updated', null, '/admin/category/list');
        }

        $view = new View(__DIR__ . '/../../application/views/category/admin_edit.phtml');
        $view->setVars(array(
            'languages'     => $this->getLanguages(),
            'category'     => $CategoryModel->getSingle($id),
        ));
        
        return $view->parse();
    }

}
