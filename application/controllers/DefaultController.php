<?php

use core\RHMVC\AbstractController;
use core\RHMVC\View;

class DefaultController extends AbstractController
{
    
    public function defaultAction()
    {
        /* @var $DefaultModel DefaultModel */
        $DefaultModel = $this->loadModel('DefaultModel');
        
        $view = new View('default/index.phtml');
        $view->setVars([
            'items' => $DefaultModel->getSampleData()
        ]);
        
        return $view->parse();
    }
    
}
