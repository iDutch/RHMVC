<?php

use core\RHMVC\AbstractModel;

class Group extends AbstractModel
{
    static $has_many = [
        ['users']
    ];
}
