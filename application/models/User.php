<?php

use core\RHMVC\AbstractModel;

class User extends AbstractModel
{
    static $belongs_to = [
        ['group']
    ];
}
