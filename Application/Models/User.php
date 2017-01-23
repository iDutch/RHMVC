<?php

namespace Application\Models;

use System\Core\RHMVC\AbstractModel;
use System\Core\RHMVC\View;
use DateTime;
use DateInterval;
use PHPMailer;

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
            $this->_setSession();
            if ($setCookie) {
                $this->_storeCookie();
            }
            return true;
        }
        $this->_messages->error('password', 'invalid password');
        return false;
    }

    public static function authenticateByCookie()
    {
        if (isset($_COOKIE['login'])) {
            list($selector, $validator) = explode(':', $_COOKIE['login']);
            $LoginToken = LoginToken::first(['conditions' => ['selector = ?', $selector], 'limit' => 1]);
            if (count($LoginToken) && hash_equals($LoginToken->token, hash('sha256', $validator))) {
                $User = self::first($LoginToken->user_id);
                $User->_setSession();
                //Refresh cookie with new selector:validator
                $LoginToken->delete();
                $User->_storeCookie();
                return true;
            }
        }
        return false;
    }

    public function requestPasswordReset()
    {
        $DateTime = new DateTime();

        $selector = uniqid();
        $validator = function_exists('random_bytes') ? bin2hex(random_bytes(32)) : bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
        $token = hash('sha256', $validator);

        $datetime = $DateTime->add(new DateInterval('PT30M'))->format('Y-m-d H:i:s');

        $PasswordrequestToken = new PasswordrequestToken();
        $PasswordrequestToken->user_id = $this->user_id;
        $PasswordrequestToken->selector = $selector;
        $PasswordrequestToken->token = $token;
        $PasswordrequestToken->expire_date = $datetime;
        $PasswordrequestToken->save();

        $Mailer = new PHPMailer();
        $Mailer->setFrom(FROM_ADDRESS, FROM_NAME);
        $Mailer->Subject = ''

        if (USE_SMTP) {
            $Mailer->isSMTP();
            $Mailer->Host = SMTP_HOST;
            $Mailer->SMTPAuth = true;
            $Mailer->Username = SMTP_USERNAME;
            $Mailer->Password = SMTP_PASSWORD;
            $Mailer->SMTPSecure = SMTP_ENCRYPTION;
            $Mailer->Port = SMTP_PORT;
        }
    }

    private function _storeCookie() {
        $DateTime = new DateTime();

        $selector = uniqid();
        $validator = function_exists('random_bytes') ? bin2hex(random_bytes(32)) : bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
        $token = hash('sha256', $validator);

        $datetime = $DateTime->add(new DateInterval('P30D'))->format('Y-m-d H:i:s');

        $LoginToken = new LoginToken();
        $LoginToken->selector = $selector;
        $LoginToken->token = $token;
        $LoginToken->user_id = $this->id;
        $LoginToken->expire_date = $datetime;
        $LoginToken->save();

        setcookie('login', $selector.':'.$validator, time() + COOKIE_TTL, COOKIE_PATH, COOKIE_DOMAIN, false, true);
    }

    private function _setSession()
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
