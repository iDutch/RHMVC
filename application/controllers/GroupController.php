<?php

class GroupController extends AbstractController
{

    use ACLTrait;

    public function admin_indexAction()
    {
        /**
         * @var $GroupModel GroupModel
         */

        $GroupModel = $this->loadModel('GroupModel');

        $view = new View(__DIR__ . '/../../application/views/group/admin_index.phtml');
        $view->setVars(array(
            'groups'    => $GroupModel->getAll(),
            'messages'  => $this->flashMessage()->display(null, false),
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
         * @var $GroupModel GroupModel
         */

        $GroupModel = $this->loadModel('GroupModel');

        if (count($_POST) > 0) {
            $GroupModel->saveGroup($_POST);
            $this->flashMessage()->success('group added', null, '/admin/group/list');
        }

        $view = new View(__DIR__ . '/../../application/views/group/admin_add.phtml');
        $view->setVars(array(
            'modules'   => $GroupModel->getModuleList(),
            'actions'   => $GroupModel->getActionList(),
            'languages' => $this->getLanguages(),
        ));

        return $view->parse();
    }

    public function admin_editAction($id)
    {
        /**
         * @var $GroupModel GroupModel
         */

        $GroupModel = $this->loadModel('GroupModel');

        if (count($_POST) > 0) {
            $GroupModel->saveGroup($_POST, $id);
            $this->flashMessage()->success('group updated', null, '/admin/group/list');
        }

        $view = new View(__DIR__ . '/../../application/views/group/admin_edit.phtml');
        $view->setVars(array(
            'modules'   => $GroupModel->getModuleList(),
            'actions'   => $GroupModel->getActionList(),
            'languages' => $this->getLanguages(),
            'group'     => $GroupModel->getSingle($id),
        ));
        
        return $view->parse();
    }

}
