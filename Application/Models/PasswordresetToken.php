<?php

namespace Application\Models;

use System\Core\RHMVC\AbstractModel;
use DateTime;
use DateInterval;

class PasswordresetToken extends AbstractModel
{

    const TOKEN_SEPARATOR = 'separator';

    static $belongs_to = [
        ['user']
    ];

    private $validator = null;

    public static function load($token) {
        list($selector, $validator) = explode(self::TOKEN_SEPARATOR, $token);
        $PasswordresetToken = self::first(['conditions' => ['selector = ?', $selector], 'limit' => 1]);
        if (count($PasswordresetToken) && hash_equals($PasswordresetToken->token, hash('sha256', $validator))) {
            return $PasswordresetToken;
        }
        return false;
    }

    public static function generate(User $user)
    {
        $DateTime = new DateTime();

        $selector = uniqid();
        $validator = function_exists('random_bytes') ? bin2hex(random_bytes(32)) : bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
        $token = hash('sha256', $validator);

        $datetime = $DateTime->add(new DateInterval('PT30M'));

        $PasswordresetToken = new self();
        $PasswordresetToken->user_id = $user->id;
        $PasswordresetToken->selector = $selector;
        $PasswordresetToken->token = $token;
        $PasswordresetToken->expires = $datetime->format('Y-m-d H:i:s');
        $PasswordresetToken->save();
        $PasswordresetToken->validator = $validator;

        return $PasswordresetToken;
    }

    public function getEmailToken()
    {
        return $this->selector.self::TOKEN_SEPARATOR.$this->validator;
    }

}
