<?php

namespace RHMVC;

use RHMVC\HMVC;
use RHMVC\DBAdapter;

abstract class AbstractModel extends HMVC
{

    protected $db_adapter = null;

    public function __construct()
    {
        $this->db_adapter = DBAdapter::getInstance();
    }

}
