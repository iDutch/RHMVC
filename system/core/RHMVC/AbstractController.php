<?php

namespace RHMVC;

abstract class AbstractController extends HMVC
{

    public function __construct($layout = null)
    {
        $this->layout = $layout; //Pass layout to controllers so they can alter the main layout when needed
    }

}
