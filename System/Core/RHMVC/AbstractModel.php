<?php

namespace System\Core\RHMVC;

use ActiveRecord\Model;
use System\Libs\Messages\Messages;

abstract class AbstractModel extends Model
{
    protected $messages = null;

    public function __construct(array $attributes=array(), $guard_attributes=true, $instantiating_via_find=false, $new_record=true)
    {
        $this->messages = Messages::getInstance();
        parent::__construct($attributes, $guard_attributes, $instantiating_via_find, $new_record);
    }

}