<?php

namespace libs\Logger;

class Logger
{

    private static $instance = null;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function log($message)
    {
        
        $date = new DateTime();
        $logmessage = $date->format('Y-m-d H:i:s') . " " . $message ."\n";

        $logfile = __DIR__ . '/../../../application/logs/adminlog-' . $date->format('Y-m-d') . '.log';

        $handle = fopen($logfile, 'a');
        fwrite($handle, $logmessage);
        fclose($handle);
    }

}
