<?php

namespace Application\Controllers;

use System\Core\RHMVC\AbstractController;
use System\Core\RHMVC\View;
use Application\Models\User;

class UserController extends AbstractController
{

    public function loginAction()
    {
        if (isset($_SESSION['user'])) {
            $this->redirect('/admin');
        }

        if (isset($_POST['login'])) {
            $user = User::first(['conditions' => ['username = ? OR emailaddress = ?', $_POST['username'], $_POST['username']]]);
            if (count($user)) {
                if ($user->authenticate($_POST['password'], isset($_POST['rememberme']))) {
                    $this->redirect('/admin');
                }
            } else {
                $this->_messages->error('emailaddress', 'invalid user');
            }
        }
        $view = new View('user/login.phtml');
        $view->setVars([
           'post' => $_POST
        ]);

        return $view->parse();
    }

}
