<?php

class UserController extends AbstractController
{

    use ACLTrait;

    public function admin_readAction()
    {
        /**
         * @var $UserModel UserModel
         */

        if (!$this->hasAccess(__FUNCTION__)) {
            header("Location: /admin/dashboard");
        }

        $UserModel = $this->loadModel('UserModel');

        $view = new View(__DIR__ . '/../../application/views/user/admin_index.phtml');
        $view->setVars(array(
            'users' => $UserModel->getAll(),
            'messages' => $this->flashMessage()->display(null, false),
            'permissions' => array(
                'create' => $this->hasAccess('create'),
                'update' => $this->hasAccess('update'),
                'delete' => $this->hasAccess('delete'),
            ),
        ));

        return $view->parse();
    }

    public function admin_createAction()
    {
        /**
         * @var $UserModel UserModel
         * @var $GroupModel GroupModel
         */

        if (!$this->hasAccess(__FUNCTION__)) {
            header("Location: /admin/user/list");
        }

        $UserModel = $this->loadModel('UserModel');
        $GroupModel = $this->loadModel('GroupModel');

        if (count($_POST) > 0) {
            if ($UserModel->saveUser($_POST)) {
                $this->flashMessage()->success('user created', null, '/admin/user/list');
            }
        }

        $view = new View(__DIR__ . '/../../application/views/user/admin_add.phtml');
        $view->setVars(array(
            'messages'  => $this->flashMessage()->display(null, false),
            'postdata'  => $_POST,
            'modules'   => $UserModel->getModuleList(),
            'actions'   => $UserModel->getActionList(),
            'groups'    => $GroupModel->getList(),
        ));

        return $view->parse();
    }

    public function admin_updateAction($id)
    {
        /**
         * @var $UserModel UserModel
         * @var $GroupModel GroupModel
         */

        if (!$this->hasAccess(__FUNCTION__)) {
            header("Location: /admin/user/list");
        }

        $UserModel = $this->loadModel('UserModel');
        $GroupModel = $this->loadModel('GroupModel');

        if (count($_POST) > 0) {
            if ($UserModel->saveUser($_POST, $id)) {
                $this->flashMessage()->success('user updated', null, '/admin/user/list');
            }
        }

        $view = new View(__DIR__ . '/../../application/views/user/admin_edit.phtml');
        $view->setVars(array(
            'messages'  => $this->flashMessage()->display(null, false),
            'postdata'  => $_POST,
            'modules'   => $UserModel->getModuleList(),
            'actions'   => $UserModel->getActionList(),
            'groups'    => $GroupModel->getList(),
            'user'      => $UserModel->getSingle($id),
        ));
        
        return $view->parse();
    }

    public function admin_loginAction()
    {
        $UserModel = $this->loadModel('UserModel');

        if (count($_POST) > 0) {
            if($user = $UserModel->authenticateUser($_POST)){
                $this->flashMessage()->success('welcome user', array($user->username), '/admin/user/list');
            }
        }

        $view = new View(__DIR__ . '/../../application/views/user/admin_login.phtml');
        $view->setVars(array(
            'messages' => $this->flashMessage()->display(null, false),
            'postdata' => $_POST,
        ));

        return $view->parse();
    }

    public function admin_logoutAction()
    {
        unset($_SESSION['user']);
        $this->flashMessage()->success('logged out', null, '/admin/login');
    }

}
