<?php

use core\RHMVC\AbstractController;
use core\RHMVC\View;

class DefaultController extends AbstractController
{
    
    public function defaultAction()
    {
        /* @var $User User */
        $User = $this->loadModel('User');
        $Article = $this->loadModel('Article');
        
        $view = new View('default/index.phtml');
        $view->setVars([
            'users'     => $User::find('all'),
            'articles'  => $Article::find('all', ['limit' => 5])
        ]);
        
        return $view->parse();
    }
    
}
