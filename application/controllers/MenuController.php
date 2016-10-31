<?php

use core\RHMVC\AbstractController;
use core\RHMVC\View;

class MenuController extends AbstractController
{
    
    public function indexAction()
    {
        $items = [];
        for ($i = 0; $i < 3; $i++) {
            $obj = new \stdClass();
            $obj->url = '#';
            $obj->name = 'Item ' . $i;
            array_push($items, $obj);
        }
        
        $view = new View('menu/index.phtml');
        $view->setVars([
            'menuitems' => $items
        ]);
        
        return $view->parse();
    }
    
}
