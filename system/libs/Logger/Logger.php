<?php


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

    public function log($action, $module, $id = null, $viewurl = null)
    {
        switch($action) {
            case 'create':
                $action = 'added';
                break;
            case 'update':
                $action = 'edited';
                break;
            case 'delete':
                $action = 'deleted';
        }
        $date = new DateTime();
        $logmessage = $date->format('Y-m-d H:i:s') . " " . $_SESSION['user']->username . " " . $action . " " . $module . ($id != null ? ' #'.$id : '') . "\n";

        $logfile = __DIR__ . '/../../../application/logs/adminlog-' . $date->format('Y-m-d') . '.log';

        $handle = fopen($logfile, 'a');
        fwrite($handle, $logmessage);
        fclose($handle);
    }

}