<?php

namespace Application\Controllers;

use Application\Models\PasswordresetToken;
use System\Core\RHMVC\AbstractController;
use System\Core\RHMVC\View;
use Application\Models\User;
use Exception;

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
                $this->_messenger->error('login', 'invalid credentials');
            }
        }
        $view = new View('user/login.phtml');
        $view->setVars([
           'post' => $_POST
        ]);

        return $view->parse();
    }

    public function requestResetPasswordAction()
    {
        if (isset($_POST['requestresetpassword'])) {
            $User = User::first(['conditions' => ['emailaddress = ?', $_POST['emailaddress']]]);
            if (count($User)) {
                $User->sendPasswordResetMail();
            }
            $this->_messenger->success('passwordreset', $this->_translator->translate('password reset mail sent', [$_POST['emailaddress']]));
        }
        $view = new View('user/request_reset_password_form.phtml');

        return $view->parse();
    }

    public function resetPasswordAction($token)
    {
        $PasswordresetToken = PasswordresetToken::load($token);
        if (!$PasswordresetToken instanceof PasswordresetToken) {
            //Cannot load so token must be invalid or expired
            $this->redirect('/admin/login');
        }
        if (isset($_POST['resetpassword'])) {
            if ($_POST['password'] == $_POST['password2']) {
                $User = User::first($PasswordresetToken->user_id);
                if (count($User)) {
                    $User->updatePassword($_POST['password']);
                    $this->_messenger->success('passwordresetsuccess', $this->_translator('password has been reset',[$this->_router->getURLByRouteName('admin/login')]));
                    $PasswordresetToken->delete();
                } else {
                    throw new Exception('UserModel Error: Cannot update password. User not found!');
                }
            } else {
                $this->_messenger->error('passwordreseterror', 'passwords don\'t match');
            }

        }
        $view = new View('user/reset_password_form.phtml');

        return $view->parse();
    }

}
