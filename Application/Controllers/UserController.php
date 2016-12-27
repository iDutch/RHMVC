<?php

namespace Application\Controllers;

use System\Core\RHMVC\AbstractController;
use System\Core\RHMVC\View;
use Application\Models\User;

class UserController extends AbstractController
{

    /**
     *
     * @return string
     */
    public function loginAction()
    {
        if (isset($_POST['login'])) {
            $user = User::first(['conditions' => ['username = ? OR email = ?', $_POST['username'], $_POST['username']]]);
            var_dump($user);
            if ($user->authenticate($_POST['password'])) {
                $this->redirect('/admin/blog');
            }
        }
        $view = new View('user/login.phtml');

        return $view->parse();
    }

}
