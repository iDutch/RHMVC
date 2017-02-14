<?php

namespace Application\Models;

use System\Core\RHMVC\AbstractModel;

class LoginToken extends AbstractModel
{

    const TOKEN_SEPARATOR = 'separator';

    static $belongs_to = [
        ['user']
    ];

    public static function load($token) {
        list($selector, $validator) = explode(self::TOKEN_SEPARATOR, $token);
        $LoginToken = self::first(['conditions' => ['selector = ?', $selector], 'limit' => 1]);
        if (count($LoginToken) && hash_equals($LoginToken->token, hash('sha256', $validator))) {
            return $LoginToken;
        }
        return false;
    }

    public static function storeLoginCookie(User $user) {
        $DateTime = new DateTime();

        $selector = uniqid();
        $validator = function_exists('random_bytes') ? bin2hex(random_bytes(32)) : bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
        $token = hash('sha256', $validator);

        $datetime = $DateTime->add(new DateInterval('P30D'))->format('Y-m-d H:i:s');

        $LoginToken = new LoginToken();
        $LoginToken->selector = $selector;
        $LoginToken->token = $token;
        $LoginToken->user_id = $user->id;
        $LoginToken->expires = $datetime;
        $LoginToken->save();

        setcookie('login', $selector.self::TOKEN_SEPARATOR.$validator, time() + COOKIE_TTL, COOKIE_PATH, COOKIE_DOMAIN, false, true);
    }

}
