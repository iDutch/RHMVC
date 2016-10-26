<?php

use core\RHMVC\AbstractController;
use core\RHMVC\View;

class DefaultController extends AbstractController
{
    
    public function defaultAction()
    {
        /* @var $User User */
        $User = $this->loadModel('User');
        $view = new View('default/index.phtml');
        $view->setVars([
            'items' => $User::find('all')
        ]);
        
        return $view->parse();
    }
    
}
