<?php

namespace Application\Models;

use System\Core\RHMVC\AbstractModel;
use System\Core\RHMVC\View;
use PHPMailer;
use Exception;

class User extends AbstractModel
{

    static $belongs_to = [
        ['group']
    ];

    static $has_many = [
        ['actions', 'through' => 'user_roles', 'select' => 'SUM(value) AS value', 'group' => 'user_roles.module_id'],
        ['modules', 'through' => 'user_roles', 'group' => 'user_roles.module_id']
    ];

    public function authenticate($password, $setCookie = false)
    {
        if (password_verify($password, $this->password)) {
            $this->setSession();
            if ($setCookie) {
                $this->storeLoginCookie();
            }
            return true;
        }
        $this->_messenger->error('login', 'invalid credentials');
        return false;
    }

    public static function authenticateByCookie()
    {
        if (isset($_COOKIE['login'])) {
            $LoginToken = LoginToken::load($_COOKIE['login']);
            if ($LoginToken instanceof LoginToken) {
                $User = self::first($LoginToken->user_id);
                $User->setSession();
                //Refresh cookie with new selector:validator
                $LoginToken->delete();
                $User->storeLoginCookie();
                return true;
            }
        }
        return false;
    }

    public function updatePassword($password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->save();
    }

    public function sendPasswordResetMail()
    {
        $PasswordresetToken = PasswordresetToken::generate($this);

        $mailer_config = $this->getConfig('mailsettings');

        $Mailer = new PHPMailer();
        $Mailer->setFrom($mailer_config['from_address'], $mailer_config['from_name']);

        $Mailer->isSMTP($mailer_config['use_smtp']);
        if ($mailer_config['use_smtp']) {
            $Mailer->Host = $mailer_config['smtp_host'];
            $Mailer->SMTPAuth = true;
            $Mailer->Username = $mailer_config['smtp_username'];
            $Mailer->Password = $mailer_config['smtp_password'];
            $Mailer->SMTPSecure = $mailer_config['smtp_encryption'];
            $Mailer->Port = $mailer_config['smtp_port'];
        }
        $Mailer->addAddress($this->emailaddress, $this->firstname . ' ' . $this->lastname);

        $Mailer->Subject = '';
        $Mailer->isHTML($mailer_config['is_html_message']);

        $message = new View('user/mail/resetpassword.'.strtolower($_SESSION['language']['iso_code']).'.phtml');
        $message->setVars([
            'fullname' => $this->firstname . ' ' . $this->lastname,
            'token' => $PasswordresetToken->getEmailToken(),
            'expire_date' => $PasswordresetToken->expires
        ]);


        if ($mailer_config['is_html_message']) {
            $Mailer->Body = $message->parse();
            $Mailer->AltBody = strip_tags($message->parse());
        } else {
            $Mailer->Body = $message;
        }

        if(!$Mailer->send()) {
            throw new Exception('UserModel error: Failed to send password reset mail! '. $Mailer->ErrorInfo);
        }
    }

    public function storeLoginCookie() {
        LoginToken::storeLoginCookie($this);
    }

    public function setSession()
    {
        $_SESSION['user'] = $this->attributes();
        $roles = [];
        foreach ($this->actions as $key => $action) {
            $action = $action->attributes();
            $module = $this->modules[$key]->attributes();
            $roles[$module['controller']] = $action['value'];
        }
        $_SESSION['user']['user_roles'] = $roles;
        session_regenerate_id();
    }

}
