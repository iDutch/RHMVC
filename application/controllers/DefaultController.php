<?php

use RHMVC\AbstractController;
use RHMVC\View;

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
