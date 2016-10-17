<?php

namespace core\RHMVC;

use core\RHMVC\HMVC;
use core\RHMVC\DBAdapter;

abstract class AbstractModel extends HMVC
{

    protected $db_adapter = null;

    public function __construct()
    {
        $this->db_adapter = DBAdapter::getInstance();
    }

}
