<?php

namespace System\Libs\Logger;

use DateTime;

class Logger
{

    const AUDIT_TRAIL = 'Audit_trail';
    const EXCEPTION = 'Exception';
    const AUTHORISATION = 'Authorisation';

    private static $instance = null;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function log($message, $type = self::AUDIT_TRAIL)
    {
        
        $date = new DateTime();
        $logmessage = "[" . $date->format('Y-m-d H:i:s') . "] " . $message ."\n";

        if (!file_exists(LOG_DIR . $type)) {
            mkdir(LOG_DIR . $type);
        }

        $logfile = LOG_DIR . $type . '/' . $date->format('Y-m-d') . '.log';

        $handle = fopen($logfile, 'a');
        fwrite($handle, $logmessage);
        fclose($handle);
    }

}
